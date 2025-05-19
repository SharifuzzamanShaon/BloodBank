const  handleLogin=async(event)=> {
    event.preventDefault();

    const form = event.target;
    const errorMsg = document.getElementById("error-msg");

    const formData = {
      username: form.username.value.trim(),
      password: form.password.value,
    };

    errorMsg.textContent = "";
    errorMsg.classList.add("d-none");

    try {
      const res = await fetch("../api/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      });

      const result = await res.json();

      if (!res.ok) throw new Error(result.error || "Login failed");

      window.location.href = result.redirect;
    } catch (err) {
      errorMsg.textContent = err.message;
      errorMsg.classList.remove("d-none");
    }
  }