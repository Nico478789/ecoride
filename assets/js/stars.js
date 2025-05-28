window.onload = () => {
  const stars = document.querySelectorAll(".bi");

  const note = document.querySelector("#comment_grade");

  let star = null; // Ensure 'star' is defined in the current scope

  for (star of stars) {
    /* Add event listeners to each star 
    on Hover, it will change the color to gold without filling the shape*/
    star.addEventListener("mouseover", function () {
      resetStars();
      this.style.color = "gold";
      let previousStar = this.previousElementSibling;
      while (previousStar) {
        previousStar.style.color = "gold";
        previousStar = previousStar.previousElementSibling;
      }
    });

    /* 
    on click, it will fill the shape for the clicked star and the previous ones*/
    star.addEventListener("click", function () {
      note.value = this.dataset.value;
      this.classList.remove("bi-star");
      this.classList.add("bi-star-fill");
      let previousStar = this.previousElementSibling;
      while (previousStar) {
        previousStar.classList.remove("bi-star");
        previousStar.classList.add("bi-star-fill");
        previousStar = previousStar.previousElementSibling;
      }
    });

    /* 
    When moving the mouse out from the stars, it will keep stars filled depending on the saved grade value*/
    star.addEventListener("mouseout", function () {
      resetStars(note.value);
    });
  }

  function resetStars(note = 0) {
    for (star of stars) {
      if (star.dataset.value > note) {
        star.style.color = "black";
        star.classList.remove("bi-star-fill");
        star.classList.add("bi-star");
      } else {
        star.style.color = "gold";
        star.classList.remove("bi-star");
        star.classList.add("bi-star-fill");
      }
    }
  }
};
