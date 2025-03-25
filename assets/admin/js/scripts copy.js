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

    // Hide all modals initially
    this.content.forEach((e) => {
      e.style.display = "none";
    });

    // Set up event listeners for triggers
    this.trigger.forEach((r) => {
      r.addEventListener("click", (e) => {
        let t = r.parentElement.querySelector(l); // Find the related modal content

        // Function to close the modal
        let closeModal = () => {
          let e = window.getComputedStyle(t).display;
          if (e === "none") e = "block";
          t.style.display = e;
          t.offsetHeight;

          // Start closing transition
          t.style.overflow = "hidden";
          t.style.opacity = 1;
          t.style.paddingTop = 0;
          t.style.paddingBottom = 0;
          t.style.marginTop = 0;
          t.style.marginBottom = 0;
          t.offsetHeight;
          t.style.boxSizing = "border-box";
          t.style.transitionProperty = "opacity";
          t.style.transitionDuration = s + "ms";
          t.style.opacity = 0;

          // Clean up after the transition
          t.style.removeProperty("padding-top");
          t.style.removeProperty("padding-bottom");
          t.style.removeProperty("margin-top");
          t.style.removeProperty("margin-bottom");

          window.setTimeout(() => {
            t.style.removeProperty("opacity");
            t.style.removeProperty("overflow");
            t.style.removeProperty("transition-duration");
            t.style.removeProperty("transition-property");
            t.style.display = "none";
          }, s);
        };

        // Open the modal if it's closed
        if ("none" === window.getComputedStyle(t).display) {
          let e = window.getComputedStyle(t).display;
          if (e === "none") e = "block";
          t.style.display = e;
          t.offsetHeight;

          // Start opening transition
          t.style.overflow = "hidden";
          t.style.opacity = 0;
          t.style.paddingTop = 0;
          t.style.paddingBottom = 0;
          t.style.marginTop = 0;
          t.style.marginBottom = 0;
          t.offsetHeight;
          t.style.boxSizing = "border-box";
          t.style.transitionProperty = "opacity";
          t.style.transitionDuration = s + "ms";
          t.style.opacity = 1;

          // Clean up
          t.style.removeProperty("padding-top");
          t.style.removeProperty("padding-bottom");
          t.style.removeProperty("margin-top");
          t.style.removeProperty("margin-bottom");

          window.setTimeout(() => {
            t.style.removeProperty("opacity");
            t.style.removeProperty("overflow");
            t.style.removeProperty("transition-duration");
            t.style.removeProperty("transition-property");
          }, s);
        } else {
          closeModal();
        }

        // Close modal when close-icon is clicked
        t.querySelectorAll(".close-icon").forEach((closeButton) => {
          closeButton.addEventListener("click", (e) => {
            closeModal();
          });
        });
      });
    });
  }
}

let modalOne = new ModalPopup({
  triggerClass: ".modal-trigger",
  contentClass: ".modal-window",
  speed: 500,
});

// Modal: End
