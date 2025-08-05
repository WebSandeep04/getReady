const steps = document.querySelectorAll(".step-content");
const indicators = document.querySelectorAll(".steps .step");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const submitBtn = document.getElementById("submitBtn");

let currentStep = 0;

// Show/hide buttons based on current step
function updateButtons() {
  if (currentStep === 0) {
    // First step - hide previous button, show next button
    prevBtn.style.display = "none";
    nextBtn.style.display = "block";
    submitBtn.style.display = "none";
  } else if (currentStep === steps.length - 1) {
    // Last step - show previous button, hide next button, show submit button
    prevBtn.style.display = "block";
    nextBtn.style.display = "none";
    submitBtn.style.display = "block";
  } else {
    // Middle steps - show both previous and next buttons, hide submit button
    prevBtn.style.display = "block";
    nextBtn.style.display = "block";
    submitBtn.style.display = "none";
  }
}

// Next button functionality
nextBtn.addEventListener("click", () => {
  if (currentStep < steps.length - 1) {
    steps[currentStep].classList.remove("active");
    indicators[currentStep].classList.remove("active");
    currentStep++;
    
    steps[currentStep].classList.add("active");
    indicators[currentStep].classList.add("active");
    updateButtons();
  }
});

// Previous button functionality
prevBtn.addEventListener("click", () => {
  if (currentStep > 0) {
    steps[currentStep].classList.remove("active");
    indicators[currentStep].classList.remove("active");
    currentStep--;
    
    steps[currentStep].classList.add("active");
    indicators[currentStep].classList.add("active");
    updateButtons();
  }
});

// Initialize button visibility
updateButtons();
