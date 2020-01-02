const ratio = .1
const options = {
    root: null,
    rootMargin: '0px' , 
    threshold: ratio
}

const handleIntersect = function(entries, observer) {
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.remove('reveal')
            observer.unobserve(entry.target)
        } 
    })

}

 

const observer = new IntersectionObserver(handleIntersect, options);
document.querySelectorAll('.reveal').forEach(function (r) {
    observer.observe(r)
})

$(".tohide").hide();
$(".btn-primary").on("click", function() {
  var target = $(this).data("target");
  if(target!==undefined) {
    $(target).toggle();
      $(this).toggleClass("active",$(target).is(":visible"));
  }
});