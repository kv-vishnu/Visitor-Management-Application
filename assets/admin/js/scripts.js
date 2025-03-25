// Tabs: Start
window.addEventListener("load", function () {
  // store tabs variable
  var myTabs = document.querySelectorAll("ul.tabs__nav > li");
  function myTabClicks(tabClickEvent) {
    for (var i = 0; i < myTabs.length; i++) {
      myTabs[i].classList.remove("active");
    }
    var clickedTab = tabClickEvent.currentTarget;
    clickedTab.classList.add("active");
    tabClickEvent.preventDefault();
    var myContentPanes = document.querySelectorAll(".tabs__pane");
    for (i = 0; i < myContentPanes.length; i++) {
      myContentPanes[i].classList.remove("active");
    }
    var anchorReference = tabClickEvent.target;
    var activePaneId = anchorReference.getAttribute("href");
    var activePane = document.querySelector(activePaneId);
    activePane.classList.add("active");
  }
  for (i = 0; i < myTabs.length; i++) {
    myTabs[i].addEventListener("click", myTabClicks);
  }
});

// Tabs: End

// Modal: Start

class ModalPopup {
  constructor({ triggerClass: e, contentClass: l, speed: s }) {
    this.trigger = document.querySelectorAll(e);
    this.content = document.querySelectorAll(l);

    // Initially set all modals to hidden
    this.content.forEach((e) => {
      e.style.display = "none"; // Ensure the modal is initially hidden
      e.style.opacity = 0; // Initially invisible
      e.style.visibility = "hidden"; // Ensure modal isn't visible
    });

    // Set up event listeners for modal trigger buttons
    this.trigger.forEach((r) => {
      r.addEventListener("click", (e) => {
        let t = r.parentElement.querySelector(l); // Find the related modal content

        // Function to close the modal with smooth transitions
        let closeModal = () => {
          t.style.transitionProperty = "opacity, visibility";
          t.style.transitionDuration = s + "ms";
          t.style.opacity = 0;
          t.style.visibility = "hidden"; // Hide the modal

          // Wait for the transition to complete, then hide the modal
          window.setTimeout(() => {
            t.style.display = "none"; // Now set display to none after transition ends
            t.style.removeProperty("transition-duration");
            t.style.removeProperty("transition-property");
          }, s); // Time equivalent to transition duration
        };

        // Open the modal if it's closed
        if (
          window.getComputedStyle(t).visibility === "hidden" ||
          window.getComputedStyle(t).opacity === "0"
        ) {
          // Set display to "block" to make the modal part of the document flow
          t.style.display = "block";
          t.style.visibility = "visible"; // Make it visible
          t.offsetHeight; // Trigger reflow so the browser notices the style change

          // Apply transition for opacity (fade-in effect)
          t.style.transitionProperty = "opacity, visibility";
          t.style.transitionDuration = s + "ms";
          t.style.opacity = 0; // Start at opacity 0 for transition
          t.offsetHeight; // Trigger reflow to ensure the transition starts
          t.style.opacity = 1; // Fade in with opacity 1

          // Clean up after transition ends
          window.setTimeout(() => {
            t.style.removeProperty("transition-duration");
            t.style.removeProperty("transition-property");
          }, s);
        } else {
          closeModal();
        }

        // Close modal when the close icon is clicked
        t.querySelectorAll(".close-icon").forEach((closeButton) => {
          closeButton.addEventListener("click", (e) => {
            closeModal();
          });
        });

        // Add event listener to close modal when clicking outside on the overlay
        t.addEventListener("click", (e) => {
          if (e.target === t) {
            closeModal();
          }
        });
      });
    });
  }
}

let modalOne = new ModalPopup({
  triggerClass: ".modal-trigger",
  contentClass: ".modal-window",
  speed: 500, // transition speed in milliseconds
});

// Modal: End

// Button Condition in Product List: Start

// Select all elements with the class 'product-list__item-buttons-block-two'
const elements = document.querySelectorAll(
  ".product-list__item-buttons-block-two"
);

// Loop through each element and check for exactly 2 <a> tags
elements.forEach((element, index) => {
  const anchorTags = element.querySelectorAll("a"); // Get all <a> tags inside the element

  if (anchorTags.length === 2) {
    // console.log(`Element ${index + 1} has exactly 2 <a> tags.`);
  } else {
    element.style.gridTemplateColumns = "1fr";
  }
});

// Button Condition in Product List: End
