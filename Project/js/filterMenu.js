document.addEventListener("DOMContentLoaded", function() {
  const sortSelect = document.getElementById('sortFilter');
  const filterInputs = document.querySelectorAll('#options-filters input[type="checkbox"]');
  const productList = document.querySelector('.product-list');
  const productCards = Array.from(productList.querySelectorAll('.product-card'));

  // Map filter names to their corresponding data attributes
  const filterToDataAttr = {
    category: 'data-category',
    brand: 'data-brand',
    skinType: 'data-skin-type',
    action: 'data-accessory',
    hairType: 'data-hair-type',
    suitableFor: 'data-suitable-for'
  };

  function getSelectedFilters() {
    const filters = {};
    filterInputs.forEach(input => {
      if (input.checked) {
        if (!filters[input.name]) filters[input.name] = [];
        filters[input.name].push(input.value);
      }
    });
    return filters;
  }

  function filterProducts() {
    const filters = getSelectedFilters();

    productCards.forEach(card => {
      let show = true;
      for (const [filterName, filterValues] of Object.entries(filters)) {
        const dataAttrName = filterToDataAttr[filterName];
        if (!dataAttrName) continue; // skip if mapping not found

        const dataAttr = card.getAttribute(dataAttrName);
        if (dataAttr === null) {
          show = false;
          break;
        }
        // For multi-value attributes (comma separated)
        const cardValues = dataAttr.split(',').map(v => v.trim().toLowerCase());
        if (!filterValues.some(val => cardValues.includes(val.toLowerCase()))) {
          show = false;
          break;
        }
      }
      card.style.display = show ? '' : 'none';
    });

    sortProducts();
  }

  function sortProducts() {
    const sortValue = sortSelect.value;
    let sortedCards = [...productCards].filter(card => card.style.display !== 'none');

    if (sortValue === 'price-asc') {
      sortedCards.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
    } else if (sortValue === 'price-desc') {
      sortedCards.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
    }

    // Re-append sorted cards
    sortedCards.forEach(card => productList.appendChild(card));
  }

  // Event listeners
  filterInputs.forEach(input => input.addEventListener('change', filterProducts));
  sortSelect.addEventListener('change', filterProducts);

  // Initial filter on page load
  filterProducts();
});

// Toggle filters
document.getElementById("toggleFiltersBtn").addEventListener("click", () => {
  const filters = document.getElementById("filters");
  filters.classList.toggle("visible");
});

document.getElementById("toggleFiltersBtn").addEventListener("click", () => {
  document.getElementById("toggleFiltersBtn").classList.toggle("visible");
});