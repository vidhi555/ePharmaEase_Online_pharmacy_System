<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<style>
  .search-wrapper {
    position: relative;
    /* VERY IMPORTANT */
    width: 250px;
  }

  #search {
    width: 100%;
    padding: 10px 14px;
    border-radius: 20px;
    border: 1px solid #ddd;
  }

  #suggestions {
    position: absolute;
    top: 100%;
    /* directly below input */
    left: 0;
    right: 0;

    background: #fff;
    border-radius: 10px;
    margin-top: 6px;
    padding: 0;
    list-style: none;

    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    display: none;
    /* hidden by default */
    z-index: 999;
    /* stays on top */
  }

  #suggestions li {
    padding: 10px 14px;
    cursor: pointer;
  }

  #suggestions li:hover {
    background: #f1f5ff;
  }
</style>

<body>
  <div class="search-wrapper">
    <input type="text" id="search" placeholder="Search products...">
    <ul id="suggestions"></ul>
  </div>
  <script>
    const data = ["Paracetamol", "Aspirin", "Vitamin C", "Cough Syrup", "Pain Relief Gel"];

    const input = document.getElementById("search");
    const suggestions = document.getElementById("suggestions");

    input.addEventListener("input", () => {
      const value = input.value.toLowerCase();
      suggestions.innerHTML = "";

      if (value === "") {
        suggestions.style.display = "none";
        return;
      }

      const filtered = data.filter(item =>
        item.toLowerCase().includes(value)
      );

      filtered.forEach(item => {
        const li = document.createElement("li");
        li.textContent = item;
        li.onclick = () => {
          input.value = item;
          suggestions.style.display = "none";
        };
        suggestions.appendChild(li);
      });

      suggestions.style.display = filtered.length ? "block" : "none";
    });
  </script>

</body>

</html>