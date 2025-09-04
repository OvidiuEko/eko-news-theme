# Eko News

Eko News este o temă WordPress „Elementor-ready”, fără dependențe SEO, cu setări de brand integrate în temă și variabile CSS expuse în `:root`.

## Instalare
- Copiază folderul `eko-news` în `wp-content/themes/`.
- Activează tema din Dashboard: `Apariție → Teme`.

## Setări de brand
- Mergi la `Apariție → Eko Theme`.
- Completează câmpurile pentru Brand (nume, logo, favicon), Colors (primary, secondary, accent, text, background) și Images (hero_default, placeholder, pattern).
- La salvare, valorile se stochează în `get_option('eko_branding')` și sunt injectate ca variabile CSS în `:root` printr-un stylesheet inline.

## Elementor: Global Colors
Mapează culorile globale folosind variabilele CSS:
- Primary = `var(--eko-primary)`
- Secondary = `var(--eko-secondary)`
- Accent = `var(--eko-accent)`
- Text = `var(--eko-text)`
- Background = `var(--eko-bg)`

Recomandare: folosește Global Colors peste tot în template-urile Elementor (Header / Footer / Single / Archive) pentru consistență vizuală.

## Detalii implementare
- PHP 7.4+; WordPress Coding Standards.
- Fără plugin-uri SEO sau hooks (tema este strict vizuală + setări brand).
- Variabilele CSS sunt generate din opțiunea `eko_branding` și incluse prin handle-ul `eko-vars` înaintea stylesheet-ului principal al temei.
- Dacă lipsește `eko_branding` la primul `after_setup_theme`, tema setează automat valori implicite (fallback-urile din `style.css`).
