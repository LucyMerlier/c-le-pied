require('bootstrap');

import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.js';

document.querySelector('#like').addEventListener('click', likePicture);

function likePicture(event)
{
    event.preventDefault();

    let likeLink = event.currentTarget;
    let link = likeLink.href;
    fetch(link)
        .then(res => res.json())
        .then(function(res) {
            let likeIcon = likeLink.firstElementChild;
            if (res.hasLiked) {
                likeIcon.classList.remove('far');
                likeIcon.classList.add('fas');
            } else {
                likeIcon.classList.remove('fas');
                likeIcon.classList.add('far');
            }
        })
    ;
}
