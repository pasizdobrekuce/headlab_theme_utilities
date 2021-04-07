export default function (docLinks, docLink) {
    const documentationLinks = document.querySelector(docLinks);
    const documentationLink = document.querySelectorAll(docLink);

    documentationLink.forEach(link => {
        const href = link.id;
        const text = link.innerHTML;
        const listItem = `<li><a href="#${href}">${text}</a></li>`;
        documentationLinks.insertAdjacentHTML('beforeend', listItem);
        link.insertAdjacentHTML('beforeend', `&nbsp;<a href="#wpwrap"><small>Top &#8593;</small></a>`)
    });
};