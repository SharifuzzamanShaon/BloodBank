async function fetchResults() {
  const form = document.getElementById("searchForm");
  const formData = new FormData(form);
  const params = new URLSearchParams(formData);
  const container = document.getElementById("resultsBody");

  container.innerHTML = `
      <div class="col-12 text-center">
        <div class="text-muted">Loading...</div>
      </div>
    `;

  try {
    const response = await fetch("./api/blood_bank.php?" + params.toString());

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    container.innerHTML = "";

    if (!data || data.length === 0) {
      container.innerHTML = `
          <div class="col-12 text-center">
            <div class="text-muted">No results found</div>
          </div>
        `;
      return;
    }

    data.forEach((bank) => {
      const card = document.createElement("div");
      card.className = "col-12 col-md-4 col-lg-3";
      card.innerHTML = `
          <div class="card h-100 shadow-sm border-danger border-1">
            <div class="card-body">
              <h5 class="card-title text-danger fw-bold">${bank.name}</h5>
              <p class="card-text mb-1"><strong>Location:</strong> ${
                bank.location
              }</p>
              <p class="card-text mb-1"><strong>Contact:</strong> ${
                bank.contact
              }</p>
              <p class="card-text mb-1"><strong>Blood Type:</strong> <span class="fw-bold">${
                bank.blood_type
              }</span></p>
              <p class="card-text">
                <strong>Availability:</strong>
                <span class="badge ${
                  bank.availability === "available" ? "bg-success" : "bg-danger"
                }">
                  ${
                    bank.availability.charAt(0).toUpperCase() +
                    bank.availability.slice(1)
                  }
                </span>
              </p>
            </div>
          </div>
        `;

      container.appendChild(card);
    });
  } catch (error) {
    console.error("Error fetching blood bank data:", error);
    container.innerHTML = `
        <div class="col-12 text-danger text-center">
          Error loading data
        </div>
      `;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fetchResults();
  document
    .getElementById("searchForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      fetchResults();
    });
});

const handleBloodRequest = async (event) => {
  event.preventDefault();

  const form = event.target;
  const errorMsg = document.getElementById("error-msg");

  const formData = {
    bloodgroup: form.blood_group.value,  // Note: PHP expects 'bloodgroup' (no underscore)
    contact: form.contact.value.trim(),
    location: form.location.value.trim(),
    quantity: form.quantity.value,
    purpose: form.purpose?.value || "",  // optional field
  };

  // Clear previous errors
  errorMsg.textContent = "";
  errorMsg.classList.add("d-none");

  try {
    const res = await fetch("../api/request.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "include",  // Important to send session cookie for PHP
      body: JSON.stringify(formData),
    });
    const text = await res.text();
    console.log("Raw response:", text);

    let result;
    try {
      result = JSON.parse(text);
    } catch (jsonErr) {
      throw new Error(
        "Server did not return valid JSON. Check server logs or browser dev tools."
      );
    }

    if (!res.ok) throw new Error(result.error || "Request failed");

    alert("Request successful! Please check your email for confirmation.");
    window.location.href = "/userDashboard.html";

  } catch (err) {
    console.error("Error:", err);
    errorMsg.textContent = err.message;
    errorMsg.classList.remove("d-none");
  }
};

