footer = document.getElementById("footer");

window.onscroll = function() {scrollFunction()};

function scrollFunction()
{
    if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
        footer.style.display = "block";
    } else {
        footer.style.display = "none";
    }
}

function backToTop()
{
    window.scroll({
        top: 0, 
        left: 0, 
        behavior: 'smooth'
    });
}

document.querySelectorAll('.like').forEach(item => {item.addEventListener('click', addToLikes)});

function addToLikes(event)
{
    event.preventDefault();
    let likeLink = event.currentTarget;
    let link = likeLink.href;
    fetch(link);
    document.location.reload();
}
