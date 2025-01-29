<div class="w-full">
    <style>
        @keyframes wiggle {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }
        .animate-wiggle {
            animation: wiggle 1s ease-in-out infinite;
        }
    </style>

    <div id="confetti-container" class="w-full rounded-lg overflow-hidden relative">
        <div class="p-6 w-full flex-col items-center justify-center">
            <p class="text-center text-2xl font-semibold text-gray-800 dark:text-gray-50">Birthday's this Month</p>
            @foreach ($birthdayEmployees as $emp)
                <div class="flex flex-col sm:flex-row justify-between w-full items-center py-2 {{ $loop->last ? '' : 'border-b border-slate-500/30' }}">
                    <div>
                        <p class="text-gray-700 dark:text-gray-200 text-sm">{{ $emp->surname . ', ' . $emp->first_name }} <span class="opacity-70">- {{ $emp->age }} yrs. old </span></p>
                    </div>
                    <div class="flex gap-4">
                        <p class="text-sm">{{ Carbon\Carbon::parse($emp->date_of_birth)->format('F d, Y') }}</p>
                        <span class="inline-block animate-wiggle">🎂</span>
                    </div>
                </div>
            @endforeach
        </div>
        <canvas id="confetti-canvas" class="absolute top-0 left-0 w-full h-full"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    const canvas = document.getElementById('confetti-canvas');
    const confettiInstance = confetti.create(canvas, { resize: true, useWorker: true });

    function playConfetti() {
        const duration = 3 * 1000;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };
        const animationEnd = Date.now() + duration;

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function () {
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) return clearInterval(interval);

            const particleCount = 50 * (timeLeft / duration);
            
            confettiInstance({
                ...defaults,
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
            });
            confettiInstance({
                ...defaults,
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
            });
        }, 250);
    }

    window.onload = playConfetti;
    setInterval(playConfetti, 10000);
</script>


