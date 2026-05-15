#!/usr/bin/env python3
from pathlib import Path
import re


ROOT = Path(__file__).resolve().parents[1]

checks = {
    "common/mail/store-ready.php": [
        "$rawStoreDomain = trim((string) $store->restaurant_domain);",
        "$storeName = Html::encode($store->name);",
        "$storeDomain = Html::encode($rawStoreDomain);",
        "$storeHref = preg_match('/^https?:\\/\\//i', $rawStoreDomain) ? Html::encode($rawStoreDomain) : null;",
        "$customDomainHref = Html::encode($customDomainUrl);",
        "$ownerGreetingName = Html::encode($store->owner_first_name ? $store->owner_first_name : $store->name);",
        "Your store <?= $storeName ?> is now ready",
        "Hi <?= $ownerGreetingName ?>,",
        "<?php if ($storeHref): ?>",
        "href='<?= $storeHref ?>'",
        "href=\"<?= $storeHref ?>\"",
        "href='<?= $customDomainHref ?>'",
    ],
    "common/mail/addon-purchased.php": [
        "use yii\\helpers\\Html;",
        "$addonName = Html::encode($addon->name);",
        "$rawStoreDomain = trim((string) $store->restaurant_domain);",
        "$storeName = Html::encode($store->name);",
        "$storeHref = preg_match('/^https?:\\/\\//i', $rawStoreDomain) ? Html::encode($rawStoreDomain) : null;",
        "$ownerGreetingName = Html::encode($store->owner_first_name ? $store->owner_first_name : $store->name);",
        "Purchase for <?= $addonName ?>",
        "<?= $addonName ?>",
        "Hi <?= $ownerGreetingName ?>,",
        "Addon <?= $addonName ?> has been added in your store",
        "href='<?= $storeHref ?>'",
    ],
    "common/mail/premium-upgrade.php": [
        "$rawStoreDomain = trim((string) $store->restaurant_domain);",
        "$storeName = Html::encode($store->name);",
        "$storeHref = preg_match('/^https?:\\/\\//i', $rawStoreDomain) ? Html::encode($rawStoreDomain) : null;",
        "$planName = Html::encode($subscription->plan->name);",
        "$ownerGreetingName = Html::encode($store->owner_first_name ? $store->owner_first_name : $store->name);",
        "Your store <?=  $storeName ?> has been upgraded to our <?= $planName ?>",
        "<?= $planName ?>",
        "Hi <?= $ownerGreetingName ?>,",
        "href='<?= $storeHref ?>'",
    ],
}

for relative_path, expected_fragments in checks.items():
    source = (ROOT / relative_path).read_text()
    for fragment in expected_fragments:
        if fragment not in source:
            raise SystemExit(f"{relative_path}: missing expected escaped fragment: {fragment}")

raw_patterns = [
    r"<\?=\s*\$store->name\s*\?>",
    r"<\?=\s*\$store->restaurant_domain\s*\?>",
    r"<\?=\s*\$customDomainUrl\s*\?>",
    r"<\?=\s*\$addon->name\s*\?>",
    r"<\?=\s*\$subscription->plan->name\s*\?>",
    r"<\?=\s*\$store->owner_first_name\s*\?\s*\$store->owner_first_name\s*:\s*\$store->name\s*\?>",
    r"href=['\"]<\?=\s*\$storeDomain\s*\?>['\"]",
]

for relative_path in checks:
    source = (ROOT / relative_path).read_text()
    for pattern in raw_patterns:
        if re.search(pattern, source):
            raise SystemExit(f"{relative_path}: found raw lifecycle email output: {pattern}")

print("store lifecycle email escaping checks passed")
