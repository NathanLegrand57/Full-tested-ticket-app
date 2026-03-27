# Commandes importantes de test (Front + Back)

Ce mémo regroupe les commandes essentielles pour tester le projet.

## 1) Billetterie Laravel (test-billetterie-app)

Place-toi dans le dossier :

```bash
cd test-billetterie-app
```

### Préparation (si besoin)

```bash
composer install
npm install
php artisan key:generate
php artisan migrate
```

### Lancer tous les tests

```bash
php artisan test
# ou
./vendor/bin/pest
```

### Front: Browser testing (Pest Browser)

Installer les navigateurs Playwright (premiere fois seulement) :

```bash
npx playwright install
```

Lancer uniquement les tests Browser :

```bash
php artisan test --testsuite=Browser
# ou
./vendor/bin/pest tests/Browser
```

Lancer un seul fichier Browser :

```bash
./vendor/bin/pest tests/Browser/Feature/AuthLoginBrowserTest.php
```

Filtrer un test Browser précis par son nom :

```bash
./vendor/bin/pest tests/Browser --filter="logs in with valid credentials"
```

Mode verbeux / arrêt au premier échec :

```bash
./vendor/bin/pest tests/Browser -v
./vendor/bin/pest tests/Browser --stop-on-failure
```

### Back: tests Feature et Unit

Lancer seulement les Feature :

```bash
php artisan test --testsuite=Feature
# ou
./vendor/bin/pest tests/Feature
```

Lancer seulement les Unit :

```bash
php artisan test --testsuite=Unit
# ou
./vendor/bin/pest tests/Unit
```

Filtrer par nom de test (Feature/Unit) :

```bash
./vendor/bin/pest --filter="nom_du_test"
```

### Front build/dev (utile pour vérifier le front hors tests)

```bash
npm run dev
npm run build
```

## 2) Microservice paiement FastAPI (test-payment-service)

```bash
cd test-payment-service
```

### Préparation

```bash
python -m venv venv
# Windows PowerShell
.\venv\Scripts\Activate.ps1
pip install -r requirements.txt
```

### Lancer les tests backend (pytest)

```bash
pytest
```

Lancer un seul fichier :

```bash
pytest tests/test_api.py
```

Filtrer un test précis :

```bash
pytest tests/test_api.py -k "refund_success"
```

## 3) Raccourcis utiles (copier-coller)

### Browser uniquement (Laravel)

```bash
cd test-billetterie-app && ./vendor/bin/pest tests/Browser
```

### Feature uniquement (Laravel)

```bash
cd test-billetterie-app && ./vendor/bin/pest tests/Feature
```

### Unit uniquement (Laravel)

```bash
cd test-billetterie-app && ./vendor/bin/pest tests/Unit
```

### Tous les tests paiement (FastAPI)

```bash
cd test-payment-service && pytest
```
