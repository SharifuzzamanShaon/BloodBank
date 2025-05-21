async function fetchBloodRequests() {
  const container = document.getElementById("blood-request-body");

  container.innerHTML = `
    <div class="carousel-item active">
      <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
        <p class="text-muted">Loading...</p>
      </div>
    </div>
  `;

  try {
    const response = await fetch("../api/get_blood_requests.php", {
      method: "GET",
      credentials: "include",
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    container.innerHTML = "";

    if (!data || data.length === 0) {
      container.innerHTML = `
        <div class="carousel-item active">
          <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <p class="text-muted">No blood requests found.</p>
          </div>
        </div>
      `;
      return;
    }

    data.forEach((req, index) => {
      const isActive = index === 0 ? "active" : "";
      const slide = document.createElement("div");
      slide.className = `carousel-item ${isActive}`;
      slide.innerHTML = `
        <div class="card mx-auto my-4" style="max-width: 500px;">
          <div class="card-body text-center">
            <h5 class="card-title text-danger">${
              req.bloodgroup
            } Blood Request</h5>
            <p><strong>Username:</strong> ${req.username}</p>
            <p><strong>Quantity:</strong> ${req.quantity}</p>
            <p><strong>Contact:</strong> ${req.contact}</p>
            <p><strong>Location:</strong> ${req.location}</p>
            <p><strong>Status:</strong> 
              <span class="badge ${
                req.status.toLowerCase() === "approved"
                  ? "bg-success"
                  : req.status.toLowerCase() === "pending"
                  ? "bg-warning text-dark"
                  : "bg-secondary"
              }">${req.status}</span>
            </p>
          </div>
        </div>
      `;
      container.appendChild(slide);
    });
  } catch (error) {
    console.error("Error fetching blood requests:", error);
    container.innerHTML = `
      <div class="carousel-item active">
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
          <p class="text-danger">Error: ${error.message}</p>
        </div>
      </div>
    `;
  }
}
// Run on page load
document.addEventListener("DOMContentLoaded", fetchBloodRequests);
//Find Donar
document.getElementById("searchForm").addEventListener("submit", function (e) {
  e.preventDefault(); // prevent page reload
  fetchResults(); // call your async function
});

async function searchDoner(event) {
  event.preventDefault(); // stop form from reloading the page

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
    const response = await fetch("../api/blood_bank.php?" + params.toString());

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    console.log(data);
    
    container.innerHTML = "";

    if (!data || data.length === 0) {
      container.innerHTML = `
        <div class="col-12 text-center">
          <div class="text-muted">No results found</div>
        </div>
      `;
    } else {
      data.forEach((bank) => {
        const card = document.createElement("div");
        card.className = "col-12 col-md-4 col-lg-3 mb-3";
        card.innerHTML = `
          <div class="card h-100 shadow-sm border-danger border-1">
            <div class="card-body">
              <h5 class="card-title text-danger fw-bold">${bank.name}</h5>
              <p class="card-text mb-1"><strong>Location:</strong> ${bank.location}</p>
              <p class="card-text mb-1"><strong>Contact:</strong> ${bank.contact}</p>
              <p class="card-text mb-1"><strong>Blood Type:</strong> <span class="fw-bold">${bank.blood_type}</span></p>
              <p class="card-text">
                <strong>Availability:</strong>
                <span class="badge ${
                  bank.availability === "available" ? "bg-success" : "bg-danger"
                }">
                  ${bank.availability.charAt(0).toUpperCase() + bank.availability.slice(1)}
                </span>
              </p>
            </div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    // Programmatically show the modal
    const modal = new bootstrap.Modal(document.getElementById('searchBlood'));
    modal.show();
  } catch (error) {
    console.error("Error fetching blood bank data:", error);
    container.innerHTML = `
      <div class="col-12 text-danger text-center">
        Error loading data
      </div>
    `;
  }
}

