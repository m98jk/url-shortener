const input = document.querySelector("#input-field");
const longUrl = document.querySelector("#input-url");
const shortUrl = document.querySelector("#new-url");
const resultDiv = document.querySelector("#output-div");
const errorDiv = document.querySelector("#error-div");
const errorMessage = document.querySelector("#error-text");
const clearButton = document.querySelector("#clear-btn");
const copyButton = document.querySelector("#copy-btn");

/* button action */
function shortenURL(event,url) {
  event.preventDefault();
  if (input.value) {
    shorten(input.value, url);
  } else {
    showError();
    hideResult();
  }
}

/* function to handle errors */
const handleError = (response) => {
  console.log(response);
  if (!response.ok) {
    errorMessage.textContent = "Please enter a valid URL.";
    showError();
    hideResult();
  } else {
    hideError();
    return response;
  }
};

/* function to get shortened url with input "url" with fetch and deal with error */
function shorten(input, url) {
  const formData = new FormData();
  formData.append("long_url", input);
  // console.log(url);

  fetch(url , {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((json) => {
      console.log(json);
      // shortUrl.innerHTML = json.link;
      shortUrl.innerHTML = json;
      showResult();
    })
    .catch((error) => {
      console.log(error);
    });
}

/* Clipboard functions */

function copyToClipboard() {
  var copyText = document.getElementById("shortenedURL");
  var textArea = document.createElement("textarea");
  textArea.value = copyText.href;
  document.body.appendChild(textArea);
  textArea.select();
  document.execCommand("Copy");
  document.body.removeChild(textArea);
  alert("Copied to clipboard!");
}

/* Clear fields */
const clearFields = () => {
  input.value = "";
  hideResult();
  hideError();
};

clearButton.addEventListener("click", (event) => {
  event.preventDefault();
  clearFields();
});

/* display/hide results and errors */
const showResult = () => {
  shortUrl.style.display = "flex";
};

const hideResult = () => {
  shortUrl.style.display = "none";
};

const showError = () => {
  errorDiv.style.display = "block";
};

const hideError = () => {
  errorDiv.style.display = "none";
};
