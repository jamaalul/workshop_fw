/**
 * Scanner module using html5-qrcode
 * Extracts scanner initialisation, beep playback, and stop/restart logic.
 */

import { Html5QrcodeScanner, Html5Qrcode } from "html5-qrcode";

export class AppScanner {
    constructor(elementId, options = {}) {
        this.elementId = elementId;
        this.html5QrCode = null;
        this.beepAudio = new Audio('/sounds/beep.mp3');
        this.onScanSuccess = options.onScanSuccess || (() => {});
        this.onScanError = options.onScanError || (() => {});
        
        this.config = {
            fps: options.fps || 10,
            qrbox: options.qrbox || { width: 250, height: 250 },
            aspectRatio: options.aspectRatio || 1.0
        };
    }

    async start() {
        if (this.html5QrCode) {
            await this.stop();
        }

        this.html5QrCode = new Html5Qrcode(this.elementId);
        
        const config = {
            fps: this.config.fps,
            qrbox: this.config.qrbox,
            aspectRatio: this.config.aspectRatio
        };

        try {
            await this.html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => {
                    this.handleSuccess(decodedText, decodedResult);
                },
                (errorMessage) => {
                    // console.log(errorMessage);
                }
            );
        } catch (err) {
            this.onScanError("Camera permission denied or not found.");
        }
    }

    async handleSuccess(decodedText, decodedResult) {
        // 1. Play beep
        this.playBeep();

        // 2. Stop scanner immediately
        await this.stop();

        // 3. Callback
        this.onScanSuccess(decodedText, decodedResult);
    }

    playBeep() {
        this.beepAudio.play().catch(e => console.error("Error playing beep:", e));
    }

    async stop() {
        if (this.html5QrCode && this.html5QrCode.isScanning) {
            try {
                await this.html5QrCode.stop();
                this.html5QrCode = null;
            } catch (err) {
                console.error("Error stopping scanner:", err);
            }
        }
    }

    async clear() {
        if (this.html5QrCode) {
            await this.stop();
        }
        document.getElementById(this.elementId).innerHTML = "";
    }
}

// Re-initialise on window unload to release camera
window.addEventListener('unload', () => {
    // This is tricky with async, but we can try
});
