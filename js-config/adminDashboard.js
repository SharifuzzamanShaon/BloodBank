    async function fetchBloodBanks() {
      try {
        const response = await fetch('./api/blood_bank.php');
        if (!response.ok) throw new Error("Network response was not ok");
        const data = await response.json();

        const tbody = document.getElementById('bloodBankTable');
        tbody.innerHTML = '';

        if (!data.length) {
          tbody.innerHTML =
            '<tr><td colspan="6" class="text-center">No records found</td></tr>';
          return;
        }

        data.forEach(bank => {
          const row = document.createElement('tr');
          row.innerHTML = `
              <td>${escapeHtml(bank.name)}</td>
              <td>${escapeHtml(bank.location)}</td>
              <td>${escapeHtml(bank.contact)}</td>
              <td>${escapeHtml(bank.blood_type)}</td>
              <td>
                  <span class="badge ${bank.availability === 'available' ? 'bg-success' : 'bg-danger'}">
                      ${capitalize(bank.availability)}
                  </span>
              </td>
              <td>
                <a href="#" class="btn btn-warning btn-sm me-1" onclick='openEditModal(${JSON.stringify(bank)})'>
                    <i class="bi bi-pencil-square"></i>
                </a>
                  <a href="#" class="btn btn-danger btn-sm"
                    onclick="handleDelete(${bank.id})">
                        <i class="bi bi-trash"></i>
                    </a>
              </td>
          `;
          tbody.appendChild(row);
        });
      } catch (error) {
        console.error("Error fetching data:", error);
        document.getElementById('bloodBankTable').innerHTML =
          '<tr><td colspan="6" class="text-center text-danger">Failed to load data</td></tr>';
      }
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function capitalize(text) {
      if (!text) return '';
      return text.charAt(0).toUpperCase() + text.slice(1);
    }

    fetchBloodBanks();

    // Event listener for the edit button
  function openEditModal(bank) {
    document.getElementById("edit-id").value = bank.id;
    document.getElementById("edit-name").value = bank.name;
    document.getElementById("edit-location").value = bank.location;
    document.getElementById("edit-contact").value = bank.contact;
    document.getElementById("edit-blood-type").value = bank.blood_type;
    document.getElementById("edit-availability").value = bank.availability;

    const editModal = new bootstrap.Modal(document.getElementById("editModal"));
    editModal.show();
  }
    // Filter table function
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("bloodBankTable");
        const rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let found = false;

            for (let j = 0; j < cells.length - 1; j++) { // Exclude the last cell (Actions)
                if (cells[j]) {
                    const txtValue = cells[j].textContent || cells[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            rows[i].style.display = found ? "" : "none";
        }
    }
//   edit form handler
    async function handleEdit(event) {
        event.preventDefault();
        const id = document.getElementById("edit-id").value;
        const name = document.getElementById("edit-name").value;
        const location = document.getElementById("edit-location").value;
        const contact = document.getElementById("edit-contact").value;
        const blood_type = document.getElementById("edit-blood-type").value;
        const availability = document.getElementById("edit-availability").value;
        const data = { id, name, location, contact, blood_type, availability }; 
        try {
            const response = await fetch('./api/edit_record.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
    
            if (response.ok) {
                alert("Record updated successfully!");
                fetchBloodBanks(); // Refresh the table
                const editModal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
                editModal.hide();
            } else {
                const errorData = await response.json();
                alert("Error: " + errorData.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("An error occurred while updating the record.");
        }
    }
    const handleDelete = async(id)=>{
        console.log(id);
        
        alert("Are you sure you want to delete this record?");
        try {
            const response = await fetch('./api/delete_record.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
    
            if (response.ok) {
                alert("Record deleted successfully!");
                fetchBloodBanks(); // Refresh the table
            } else {
                const errorData = await response.json();
                alert("Error: " + errorData.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("An error occurred while deleting the record.");
        }
    }