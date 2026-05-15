<?php

namespace App\Traits;

use Throwable;
use App\Helpers\AuditHelper;
use Illuminate\Support\Facades\DB;

trait SecureCrud
{
    protected function secureStore(
        string $modelClass,
        array $data
    ) {
        try {

            return DB::transaction(function () use (
                $modelClass,
                $data
            ) {

                $record = $modelClass::create($data);

                AuditHelper::log(
                    'create',
                    'success',
                    'Data created',
                    $record,
                    $data
                );

                return $record;
            });

        } catch (Throwable $e) {

            AuditHelper::log(
                'create',
                'error',
                $e->getMessage(),
                null,
                $data
            );

            throw $e;
        }
    }


    protected function secureUpdate(
        $model,
        array $data
    ) {

        try {

            return DB::transaction(function () use (
                $model,
                $data
            ) {

                $model->update($data);

                AuditHelper::log(
                    'update',
                    'success',
                    'Data updated',
                    $model,
                    $data
                );

                return $model;
            });

        } catch (Throwable $e) {

            AuditHelper::log(
                'update',
                'error',
                $e->getMessage(),
                $model
            );

            throw $e;
        }
    }


    protected function secureDelete($model)
    {
        try {

            DB::transaction(function () use ($model) {

                $model->delete();

                AuditHelper::log(
                    'delete',
                    'success',
                    'Soft deleted',
                    $model
                );
            });

        } catch (Throwable $e) {

            AuditHelper::log(
                'delete',
                'error',
                $e->getMessage(),
                $model
            );

            throw $e;
        }
    }
}
