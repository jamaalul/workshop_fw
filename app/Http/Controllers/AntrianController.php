<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    // ── Shared ───────────────────────────────────────────────────────────────

    /**
     * Rebuild and store the antrian_state cache from the database.
     * Must be called after every mutation.
     */
    private function refreshCache(): void
    {
        $current = Queue::called()->select('id', 'number', 'name', 'called_at')->first();
        $waiting = Queue::waiting()->select('id', 'number', 'name')->get()->toArray();
        $late    = Queue::late()->select('id', 'number', 'name')->get()->toArray();

        Cache::put('antrian_state', [
            'current' => $current ? [
                'id'     => $current->id,
                'number' => $current->number,
                'name'   => $current->name,
            ] : null,
            'waiting' => $waiting,
            'late'    => $late,
        ]);
    }

    // ── /guest ────────────────────────────────────────────────────────────────

    public function guestForm()
    {
        return view('antrian.guest');
    }

    public function guestStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $queue = Queue::create([
            'name'   => $validated['name'],
            'number' => Queue::nextNumber(),
            'status' => 'waiting',
        ]);

        $this->refreshCache();

        // Open ticket in new tab via redirect-then-JS trick
        return redirect()->route('antrian.tiket', $queue->id);
    }

    // ── /tiket/{id} ──────────────────────────────────────────────────────────

    public function tiket(Queue $queue)
    {
        return view('antrian.tiket', compact('queue'));
    }

    // ── /queue-board ─────────────────────────────────────────────────────────

    public function showQueueBoard()
    {
        $state = Cache::get('antrian_state', [
            'current' => null,
            'waiting' => [],
            'late'    => [],
        ]);

        return view('antrian.queue-board', compact('state'));
    }

    // ── /queue-management ────────────────────────────────────────────────────

    public function queueManage()
    {
        $waiting = Queue::waiting()->select('id', 'number', 'name')->get();
        $late    = Queue::late()->select('id', 'number', 'name')->get();
        $current = Queue::called()->select('id', 'number', 'name', 'called_at')->first();

        return view('antrian.queue-management', compact('waiting', 'late', 'current'));
    }

    // ── SSE endpoint ─────────────────────────────────────────────────────────

    public function stream(Request $request)
    {
        set_time_limit(0);
        ignore_user_abort(true);

        return response()->stream(function () {
            while (true) {
                $data = Cache::get('antrian_state', [
                    'current' => null,
                    'waiting' => [],
                    'late'    => [],
                ]);

                echo 'event: queue-update' . PHP_EOL;
                echo 'data: ' . json_encode($data) . PHP_EOL;
                echo PHP_EOL;

                ob_flush();
                flush();

                if (connection_aborted()) {
                    break;
                }

                sleep(1);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    // ── /queue-management/panggil ────────────────────────────────────────────

    public function panggil(Request $request)
    {
        // Move any currently-called entry to "late"
        Queue::called()->each(function (Queue $q) {
            $q->update(['status' => 'late']);
        });

        // Grab the next waiting entry (lowest number)
        $next = Queue::waiting()->first();

        if (! $next) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrian menunggu.',
            ], 422);
        }

        $next->update([
            'status'    => 'called',
            'called_at' => now(),
        ]);

        $this->refreshCache();

        return response()->json(['success' => true]);
    }

    // ── /queue-management/panggil-terlambat/{id} ─────────────────────────────

    public function panggilTerlambat(Queue $queue)
    {
        if ($queue->status !== 'late') {
            return response()->json([
                'success' => false,
                'message' => 'Antrian ini bukan berstatus terlambat.',
            ], 422);
        }

        // Move any currently-called entry to "late"
        Queue::called()->each(function (Queue $q) {
            $q->update(['status' => 'late']);
        });

        $queue->update([
            'status'    => 'called',
            'called_at' => now(),
        ]);

        $this->refreshCache();

        return response()->json(['success' => true]);
    }

    // ── /queue-management/selesai/{id} ───────────────────────────────────────

    public function selesai(Queue $queue)
    {
        if ($queue->status !== 'called') {
            return response()->json([
                'success' => false,
                'message' => 'Antrian ini tidak sedang dipanggil.',
            ], 422);
        }

        $queue->update([
            'status' => 'done',
        ]);

        $this->refreshCache();

        return response()->json(['success' => true]);
    }
}
