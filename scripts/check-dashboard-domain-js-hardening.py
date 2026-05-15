#!/usr/bin/env python3
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
VIEW = ROOT / "frontend/views/site/index.php"
text = VIEW.read_text()

expected_snippets = [
    "$currencyCode = (string) $restaurant->currency->code;",
    "$currencyCodeJson = json_encode($currencyCode, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);",
    "$rawRestaurantDomain = trim((string) $restaurant->restaurant_domain);",
    "$restaurantDomainText = Html::encode($rawRestaurantDomain);",
    "$restaurantDomainHref = preg_match('/^https?:\\/\\//i', $rawRestaurantDomain) ? Html::encode($rawRestaurantDomain) : null;",
    "var currency_code = <?= $currencyCodeJson ?>;",
    "str_contains($rawRestaurantDomain, '.plugn.store')",
    "<?php if ($restaurantDomainHref): ?>",
    'rel="noopener noreferrer"',
    'href="<?= $restaurantDomainHref ?>"',
    "<?= $restaurantDomainText ?>",
    "<?php else: ?><span><?= $restaurantDomainText ?></span><?php endif; ?>",
]

missing = [snippet for snippet in expected_snippets if snippet not in text]
if missing:
    raise SystemExit(f"{VIEW.relative_to(ROOT)} is missing dashboard hardening snippets: {missing}")

forbidden_snippets = [
    'var currency_code = "<?= $currencyCode ?>";',
    'href="<?= $restaurant->restaurant_domain ?>"',
    "<?= $restaurant->restaurant_domain ?>",
    "str_contains($restaurant->restaurant_domain, '.plugn.store')",
]

present = [snippet for snippet in forbidden_snippets if snippet in text]
if present:
    raise SystemExit(f"{VIEW.relative_to(ROOT)} still contains raw dashboard rendering snippets: {present}")

print("dashboard domain/js hardening checks passed")
