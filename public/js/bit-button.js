document.addEventListener('DOMContentLoaded', () => {
    const OPTIONS = {
        duration: 300,
        ease: 'ease'
    };

    document
        .querySelectorAll('.bit-button')
        .forEach(button => {
            button.addEventListener('click', () => clickButton(button));
        });

    document
        .querySelectorAll('.bit-close-button')
        .forEach(closeButton => {
            closeButton.addEventListener('click', () => clickButton(closeButton));
        });

    function clickButton(button) {
        const target = document.getElementById(button.getAttribute('aria-controls'));
        const isExpandedBeforeClick = target.getAttribute('aria-expanded') === 'true';
        if (!isExpandedBeforeClick)
            target.setAttribute('aria-expanded', 'true');

        requestAnimationFrame(() => {
            if (!isExpandedBeforeClick)
                animateIn(target);
            else
                animateOut(target);
        });
    }

    function animateIn(bit) {
        bit.animate([
                {
                    opacity: 0,
                    transform: 'scale(0)'
                },
                {
                    opacity: 1,
                    transform: 'scale(1)'
                }
            ],
            OPTIONS
        );
    }

    function animateOut(bit) {
        const outAnimation = bit.animate([
                {
                    opacity: 1,
                    transform: 'scale(1)'
                },
                {
                    opacity: 0,
                    transform: 'scale(0)'
                }
            ],
            OPTIONS
        );

        outAnimation.onfinish = () => {
            bit.setAttribute('aria-expanded', 'false');
        };
    }
});