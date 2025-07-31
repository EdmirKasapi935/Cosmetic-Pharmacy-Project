<?php include_once __DIR__ . '/templates/header.php'; ?>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
        background: #fff;

    }

    .hero {
        height: 100px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        color: #2E86C1;
        font-size: 28px;
        font-weight: bold;
        padding: 30px;
        text-shadow: 1px 1px 3px #fff;
    }


    .text{
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        line-height: 1.6;
        color: #333;
    }
    .text p {

        padding-left: 30px;
        font-size: 17px;
        line-height: 1.6;
        color: #333;
    }

    .text strong {
        color: #000;
    }
    header {
        background-color: #2E86C1;
        padding: 20px;
        text-align: center;
    }

    header h1 {
        color: white;
        font-size: 36px;
        margin-bottom: 10px;
    }
</style>
<body>
<header>
    <h1>
        Gjurmimi i dërgeses
    </h1>
</header>
<div class="text">
    <p>
        Gjurmimi i dërgeses është i mundur vetëm për porositë në Shqipëri
        (pervec qytetit të Tiranes.
    </p>
    <p>
        Pas përfundimit të blerjes online, ju do të mund të gjurmoni
        porosin tuaj dhe të jeni në dijeni të vendodhjes së saj në cdo moment.
    </p>
    <p>
        Nëpërmejt një e-mail-i, ju do të pajiseni më kodin e porosisë, me të cilin
        mund të gjurmoni dërgesen.

        Gjurmimi i porosis nuk është i mundur për porosite në Tirane. Në këtë rast,
        mund të kontaktoni Cosmetic Pharamcy, për informacion në lidhje më vendodhjen e porosis suaj.
    </p>
</div>
</body>
<?php include_once __DIR__ . '/templates/footer.php'; ?>
