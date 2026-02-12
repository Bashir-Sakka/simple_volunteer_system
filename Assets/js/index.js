const checkbox = document.querySelector("#check_kottab");
const sonInput = document.querySelector("#son_name");
if (checkbox) {
  sonInput.style.display = "none";
  checkbox.addEventListener("change", function () {
    sonInput.style.display = this.checked ? "block" : "none";
  });
}

// Live search logic
const input = document.getElementById("searchInput");
const defaultResults = document.getElementById("defaultResults");
const resultsDiv = document.getElementById("searchResults");

if (input && defaultResults && resultsDiv) {
  input.addEventListener("input", () => {
    const query = input.value.trim();

    // If empty → show default results
    if (query === "") {
      defaultResults.style.display = "block";
      resultsDiv.innerHTML = "";
      return;
    }

    // Hide defaults
    defaultResults.style.display = "none";

    fetch(
      `/Furqan1/Controller/SearchController.php?search=${encodeURIComponent(query)}`,
    )
      .then((res) => res.text())
      .then((html) => {
        resultsDiv.innerHTML = html;
      })
      .catch((err) => {
        console.error("Search error:", err);
      });
  });
}
