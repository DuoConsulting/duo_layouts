// If object-fit is supported, add a class to the html tag.
if ('objectFit' in document.documentElement.style === true) {
  document.documentElement.classList.add('object-fit');
} else {
  document.documentElement.classList.add('no-object-fit');
}
