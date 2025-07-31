document.addEventListener("DOMContentLoaded", () => {
  let cartCount = 0;
  const cartDisplay = document.getElementById("cart");
  const addToCartButtons = document.querySelectorAll(".add-to-cart");
  const currencySelector = document.getElementById("currency");
  const languageSelector = document.getElementById("language");
  const categoryDropdown = document.getElementById("categoryDropdown");

  const translations = {
    sq: {
      choose: "Zgjidh",
      protection: "MBROJTJEN NDAJ DIELLIT",
      fits_skin: "që i përshtatet lëkurës tënde",
      offer_products: "Produkte në ofertë",
      spring_offers: "OFERTAT E PRANVERËS",
      add_to_cart: "Shto në shportë",
      about_us: "Rreth nesh",
      about_text: "FarmaOn është farmacia juaj online me produkte cilësore dhe shërbim të besueshëm.",
      contact_us: "Na kontaktoni",
      social: "Rrjetet sociale",
    },
    en: {
      choose: "Choose",
      protection: "SUN PROTECTION",
      fits_skin: "that fits your skin",
      offer_products: "Products on Offer",
      spring_offers: "SPRING OFFERS",
      add_to_cart: "Add to Cart",
      about_us: "About Us",
      about_text: "FarmaOn is your trusted online pharmacy with quality products.",
      contact_us: "Contact Us",
      social: "Social Media",
    },
  };

  // Add to cart
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", () => {
      cartCount++;
      cartDisplay.textContent = `Cart (${cartCount})`;
    });
  });

  // Currency switch
  const exchangeRates = {
    ALL: 1,
    EUR: 0.0094,
    USD: 0.0103,
  };

  const currencySymbols = {
    ALL: "L",
    EUR: "€",
    USD: "$",
  };

  currencySelector.addEventListener("change", () => {
    const selected = currencySelector.value;
    const rate = exchangeRates[selected];
    const symbol = currencySymbols[selected];
    const currencyHidden = document.getElementById('currency-hidden');
    if (currencyHidden) {
      currencyHidden.value = selected;
    }

    document.querySelectorAll(".price").forEach((price) => {
      const basePrice = parseFloat(price.getAttribute("data-price"));
      const converted = (basePrice * rate).toFixed(2);
      price.textContent = `${converted} ${symbol}`;
    });
  });

  // Language switch
  languageSelector.addEventListener("change", () => {
    const lang = languageSelector.value;
    document.querySelectorAll("[data-i18n]").forEach((el) => {
      const key = el.getAttribute("data-i18n");
      el.textContent = translations[lang][key];
    });
  });

  // Mega menu functionality
  const menuButtons = document.querySelectorAll(".has-mega-menu");
  const megaMenus = document.querySelectorAll(".mega-menu");

  menuButtons.forEach((btn) => {
    btn.addEventListener("mouseenter", () => {
      const id = btn.id.replace("-btn", "-menu");
      megaMenus.forEach((menu) => (menu.style.display = "none"));
      const activeMenu = document.getElementById(id);
      if (activeMenu) activeMenu.style.display = "flex";
    });
  });

  document.addEventListener("click", (e) => {
    if (
        ![...menuButtons].some((btn) => btn.contains(e.target)) &&
        ![...megaMenus].some((menu) => menu.contains(e.target))
    ) {
      megaMenus.forEach((menu) => (menu.style.display = "none"));
    }
  });

  megaMenus.forEach((menu) => {
    menu.addEventListener("mouseleave", () => {
      menu.style.display = "none";
    });
  });
  const questionsQuiz = document.querySelectorAll('.question-quiz');
  const summarySectionQuiz = document.getElementById('summary-quiz');
  const summaryImagesQuiz = document.getElementById('summaryImages-quiz');
  const recommendationsListQuiz = document.getElementById('recommendations-quiz');

  let currentQuestionQuiz = 0;
  const answersQuiz = [];

  function showQuestionQuiz(indexQuiz) {
    questionsQuiz.forEach((qQuiz, iQuiz) => qQuiz.classList.toggle('active-quiz', iQuiz === indexQuiz));
    summarySectionQuiz.classList.remove('active-quiz');
  }

  window.nextQuestionQuiz = function () {
    if (currentQuestionQuiz < questionsQuiz.length - 1) {
      currentQuestionQuiz++;
      showQuestionQuiz(currentQuestionQuiz);
    } else {
      showSummaryQuiz();
    }
  };


  window.prevQuestionQuiz = function () {
    if (currentQuestionQuiz > 0) {
      currentQuestionQuiz--;
      showQuestionQuiz(currentQuestionQuiz);
    }
  };

  document.querySelectorAll('.options-quiz').forEach(optionGroupQuiz => {
    optionGroupQuiz.addEventListener('click', eQuiz => {
      const buttonQuiz = eQuiz.target.closest('.image-button-quiz');
      if (!buttonQuiz) return;

      const allButtonsQuiz = optionGroupQuiz.querySelectorAll('.image-button-quiz');
      allButtonsQuiz.forEach(btnQuiz => btnQuiz.classList.remove('selected-quiz'));
      buttonQuiz.classList.add('selected-quiz');

      const questionIndexQuiz = +buttonQuiz.closest('.question-quiz').dataset.questionIndex;
      const valueQuiz = buttonQuiz.dataset.value;
      const imageSrcQuiz = buttonQuiz.querySelector('img').src;
      answersQuiz[questionIndexQuiz] = { value: valueQuiz, image: imageSrcQuiz };
    });
  });

  function showSummaryQuiz() {
    questionsQuiz.forEach(qQuiz => qQuiz.classList.remove('active-quiz'));
    summarySectionQuiz.classList.add('active-quiz');
    summaryImagesQuiz.innerHTML = '';
    recommendationsListQuiz.innerHTML = '';

    answersQuiz.forEach((answerQuiz, iQuiz) => {
      const imgBtnQuiz = document.createElement('div');
      imgBtnQuiz.className = 'image-button-quiz';
      imgBtnQuiz.innerHTML = `<img src="${answerQuiz.image}" alt="Answer ${iQuiz + 1}">`;
      summaryImagesQuiz.appendChild(imgBtnQuiz);
    });

    const recommendedProductsQuiz = getRecommendationsQuiz(answersQuiz);
    recommendedProductsQuiz.forEach(productQuiz => {
      const liQuiz = document.createElement('li');
      liQuiz.textContent = productQuiz;
      recommendationsListQuiz.appendChild(liQuiz);
    });
  }

  function getRecommendationsQuiz(answersQuiz) {
    const productsQuiz = [];

    if (answersQuiz.some(ansQuiz => ansQuiz.value === 'red')) productsQuiz.push("Red Power Sneakers");
    if (answersQuiz.some(ansQuiz => ansQuiz.value === 'green')) productsQuiz.push("Eco-Friendly Yoga Mat");
    if (answersQuiz.some(ansQuiz => ansQuiz.value === 'blue')) productsQuiz.push("Ocean Breeze Cologne");

    if (answersQuiz.some(ansQuiz => ansQuiz.value === 'sunny')) productsQuiz.push("Sunglasses");
    if (answersQuiz.some(ansQuiz => ansQuiz.value === 'magical')) productsQuiz.push("Fantasy Art Book");
    if (answersQuiz.some(ansQuiz => ansQuiz.value === 'calm')) productsQuiz.push("Aromatherapy Candle");

    return productsQuiz.length ? productsQuiz : ["No specific recommendations."];
  }

  const errorMsg = document.querySelector('.cart-error');
  if (errorMsg) {
    setTimeout(() => {
      errorMsg.classList.add('hide');
    }, 4000); // 4 seconds
  }
});
