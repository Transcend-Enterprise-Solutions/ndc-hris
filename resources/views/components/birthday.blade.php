<div class="relative bg-indigo-200 dark:bg-indigo-500 p-4 sm:p-6 rounded-sm overflow-hidden mb-8">
    <!-- Original decorative SVG -->
    <div class="absolute right-0 top-0 -mt-4 mr-16 pointer-events-none hidden xl:block" aria-hidden="true">
        <svg width="319" height="198" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <path id="welcome-a" d="M64 0l64 128-64-20-64 20z" />
                <path id="welcome-e" d="M40 0l40 80-40-12.5L0 80z" />
                <path id="welcome-g" d="M40 0l40 80-40-12.5L0 80z" />
                <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="welcome-b">
                    <stop stop-color="#A5B4FC" offset="0%" />
                    <stop stop-color="#818CF8" offset="100%" />
                </linearGradient>
                <linearGradient x1="50%" y1="24.537%" x2="50%" y2="100%" id="welcome-c">
                    <stop stop-color="#4338CA" offset="0%" />
                    <stop stop-color="#6366F1" stop-opacity="0" offset="100%" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <div class="relative">
        <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold mb-1">
            Happy Birthday, {{ Auth::user()->name }} 
            <span class="inline-block animate-wiggle">🎂</span>
        </h1>
        <p class="dark:text-indigo-200">Have a blessed and joyful day! <br>
            - NDC
        </p>
    </div>
</div>


<style>
    @keyframes wiggle {
        0%, 100% { transform: rotate(-5deg); }
        50% { transform: rotate(5deg); }
    }
    .animate-wiggle {
        animation: wiggle 1s ease-in-out infinite;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    function playConfetti() {
        const duration = 3 * 1000;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };
        const animationEnd = Date.now() + duration;

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);

            confetti({
                ...defaults,
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
            });
            confetti({
                ...defaults,
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
            });
        }, 250);
    }

    window.onload = function() {
        playConfetti();
    }
    
    setInterval(playConfetti, 10000);
</script>
