<?php

namespace App\Mixins\Migrators;

class MigrationConfig
{
    protected static ?string $runtimeUrl = null;
    protected static ?string $runtimePassword = null;

    public static function getBaseUrl(): string
    {
        return rtrim(static::$runtimeUrl ?? config('migration.source_url'), '/');
    }

    public static function setBaseUrl(string $url): void
    {
        static::$runtimeUrl = $url;
    }

    public static function getPassword(): string
    {
        return static::$runtimePassword ?? config('migration.source_password');
    }

    public static function setPassword(string $password): void
    {
        static::$runtimePassword = $password;
    }

    /**
     * Map of source table names (without wp_ prefix) to local table + repo class.
     */
    public static function getTables(): array
    {
        // Ordered by dependency — import top to bottom
        return [
            'users' => [
                'order' => 1,
                'label' => 'Users',
                'local_table' => 'users',
                'migrator' => UsersMigrator::class,
            ],
            'frm_forms' => [
                'order' => 2,
                'label' => 'Forms',
                'local_table' => 'forms',
                'migrator' => FormsMigrator::class,
            ],
            'frm_fields' => [
                'order' => 3,
                'label' => 'Form Fields',
                'local_table' => 'form_fields',
                'migrator' => FormFieldsMigrator::class,
            ],
            'frm_items' => [
                'order' => 4,
                'label' => 'Form Items',
                'local_table' => 'form_items',
                'migrator' => FormItemsMigrator::class,
            ],
            'frm_item_metas' => [
                'order' => 5,
                'label' => 'Form Item Metas',
                'local_table' => 'form_item_metas',
                'migrator' => FormItemMetasMigrator::class,
            ],
            'frm_payments' => [
                'order' => 6,
                'label' => 'Payments',
                'local_table' => 'form_payments',
                'migrator' => FormPaymentsMigrator::class,
            ],
            'frm_payments_authnet' => [
                'order' => 7,
                'label' => 'Payments Authnet',
                'local_table' => 'payments_authnet',
                'migrator' => PaymentsAuthnetMigrator::class,
            ],
            'frm_payments_failed' => [
                'order' => 7,
                'label' => 'Payments Failed',
                'local_table' => 'payments_failed',
                'migrator' => PaymentsFailedMigrator::class,
            ],
            'frm_refunds_authnet' => [
                'order' => 7,
                'label' => 'Refunds Authnet',
                'local_table' => 'refunds_authnet',
                'migrator' => RefundsAuthnetMigrator::class,
            ],
            'frm_easypost_shipments' => [
                'order' => 8,
                'label' => 'Easypost Shipments',
                'local_table' => 'easypost_shipments',
                'migrator' => EasypostShipmentsMigrator::class,
            ],
            'frm_easypost_shipment_addresses' => [
                'order' => 9,
                'label' => 'Easypost Addresses',
                'local_table' => 'easypost_shipment_addresses',
                'migrator' => EasypostShipmentAddressesMigrator::class,
            ],
            'frm_easypost_shipment_label' => [
                'order' => 9,
                'label' => 'Easypost Labels',
                'local_table' => 'easypost_shipment_labels',
                'migrator' => EasypostShipmentLabelsMigrator::class,
            ],
            'frm_easypost_shipment_parcel' => [
                'order' => 9,
                'label' => 'Easypost Parcels',
                'local_table' => 'easypost_shipment_parcels',
                'migrator' => EasypostShipmentParcelsMigrator::class,
            ],
            'frm_easypost_shipment_rate' => [
                'order' => 9,
                'label' => 'Easypost Rates',
                'local_table' => 'easypost_shipment_rates',
                'migrator' => EasypostShipmentRatesMigrator::class,
            ],
            'frm_easypost_shipment_history' => [
                'order' => 9,
                'label' => 'Easypost History',
                'local_table' => 'easypost_shipment_history',
                'migrator' => EasypostShipmentHistoryMigrator::class,
            ],
            'frm_midigator_preventions' => [
                'order' => 10,
                'label' => 'Midigator Preventions',
                'local_table' => 'midigator_preventions',
                'migrator' => MidigatorPreventionsMigrator::class,
            ],
            'frm_midigator_rdr' => [
                'order' => 10,
                'label' => 'Midigator RDR',
                'local_table' => 'midigator_rdr',
                'migrator' => MidigatorRdrMigrator::class,
            ],
            'frm_midigator_resolves' => [
                'order' => 11,
                'label' => 'Midigator Resolves',
                'local_table' => 'midigator_resolves',
                'migrator' => MidigatorResolvesMigrator::class,
            ],
            'frm_midigator_resolve_history' => [
                'order' => 12,
                'label' => 'Midigator Resolve History',
                'local_table' => 'midigator_resolve_history',
                'migrator' => MidigatorResolveHistoryMigrator::class,
            ],
        ];
    }
}
