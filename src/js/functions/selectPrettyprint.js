export default function (elements) {
    const els = document.querySelectorAll(elements);
    els.forEach(function (el) {
        el.addEventListener('click', function (event) {
            event.preventDefault();
            window.getSelection()
                .selectAllChildren(el);
        });
    });
};