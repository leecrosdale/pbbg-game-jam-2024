<div x-data="countdown()" class="text-center font-semibold">
    <p>Next Turn In:</p>
    <span x-text="minutesRemaining"></span> minutes, <span x-text="secondsRemaining"></span> seconds
</div>

<script>
    function countdown() {
        return {
            minutesRemaining: 0,
            secondsRemaining: 0,
            intervalId: null,

            init() {
                this.calculateTime();
                this.intervalId = setInterval(() => {
                    this.calculateTime();
                }, 1000);
            },

            calculateTime() {
                const now = new Date();
                const nextTurn = new Date(now);
                const minutes = now.getMinutes();

                // Set next quarter-hour mark (15, 30, 45, or 0 of the next hour)
                nextTurn.setMinutes(Math.ceil((minutes + 1) / 15) * 15);
                nextTurn.setSeconds(0);

                // Calculate time remaining
                const timeDiff = nextTurn - now;
                this.minutesRemaining = Math.floor(timeDiff / 1000 / 60);
                this.secondsRemaining = Math.floor((timeDiff / 1000) % 60);

                // Reset if countdown reaches zero
                if (timeDiff <= 0) {
                    clearInterval(this.intervalId);
                    this.init();
                }
            }
        };
    }
</script>
