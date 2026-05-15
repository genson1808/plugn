#!/usr/bin/env python3
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

checks = {
    "common/mail/store-ready.php": [
        "$storeName = Html::encode($store->name);",
        "$storeOwnerName = Html::encode($store->owner_first_name ? $store->owner_first_name : $store->name);",
        "$storeDomainText = Html::encode($store_domain);",
        "$storeDomainHref = Html::encode($store_domain);",
        "$customDomainHref = Html::encode($customDomainUrl);",
        "Your store <b><?= $storeName ?></b>",
        "Hi <?= $storeOwnerName ?>,",
        "<b><?= $storeDomainText ?></b>",
        "href='<?= $storeDomainHref ?>'",
        'href="<?= $storeDomainHref ?>"',
        "href='<?= $customDomainHref ?>'",
    ],
    "common/mail/addon-purchased.php": [
        r"use yii\helpers\Html;",
        "$addonName = Html::encode($addon->name);",
        "$storeName = Html::encode($store->name);",
        "$storeOwnerName = Html::encode($store->owner_first_name ? $store->owner_first_name : $store->name);",
        "$storeDomainHref = Html::encode($store->restaurant_domain);",
        "Purchase for <?= $addonName ?>",
        "Hi <?= $storeOwnerName ?>,",
        "Addon <?= $addonName ?>",
        "href='<?= $storeDomainHref ?>'",
        "><?= $storeName ?></a>",
    ],
    "common/mail/premium-upgrade.php": [
        "$storeName = Html::encode($store->name);",
        "$storeOwnerName = Html::encode($store->owner_first_name ? $store->owner_first_name : $store->name);",
        "$storeDomainHref = Html::encode($store->restaurant_domain);",
        "$planName = Html::encode($subscription->plan->name);",
        "Your store <?=  $storeName ?> has been upgraded to our <?= $planName ?>",
        "Hi <?= $storeOwnerName ?>,",
        "href='<?= $storeDomainHref ?>'",
        "><?= $storeName ?></a>",
        "upgraded to our <?= $planName ?>",
    ],
}

for relative_path, expected_snippets in checks.items():
    text = (ROOT / relative_path).read_text()
    missing = [snippet for snippet in expected_snippets if snippet not in text]
    if missing:
        raise SystemExit(
            f"{relative_path} is missing lifecycle email escaping guard snippets: {missing}"
        )

raw_patterns = [
    ("common/mail/store-ready.php", "Your store <b><?= $store->name ?></b>"),
    ("common/mail/store-ready.php", "Hi <?= $store->owner_first_name ? $store->owner_first_name : $store->name ?>,"),
    ("common/mail/store-ready.php", "<b><?= $store->restaurant_domain ?></b>"),
    ("common/mail/store-ready.php", "href='<?= $customDomainUrl ?>'"),
    ("common/mail/addon-purchased.php", "Purchase for <?= $addon->name ?>"),
    ("common/mail/addon-purchased.php", "Hi <?= $store->owner_first_name ? $store->owner_first_name : $store->name ?>,"),
    ("common/mail/addon-purchased.php", "Addon <?= $addon->name ?>"),
    ("common/mail/addon-purchased.php", "><?= $store->name ?></a>"),
    ("common/mail/premium-upgrade.php", "<?= $subscription->plan->name ?>"),
    ("common/mail/premium-upgrade.php", "><?= $store->name ?></a>"),
]

for relative_path, raw_pattern in raw_patterns:
    text = (ROOT / relative_path).read_text()
    if raw_pattern in text:
        raise SystemExit(f"{relative_path} still contains raw lifecycle output: {raw_pattern}")

print("lifecycle email rendering guard passed")
