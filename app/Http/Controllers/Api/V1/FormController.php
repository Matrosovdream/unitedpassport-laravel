<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Actions\Api\V1\Form\AddFormFieldAction;
use App\Http\Actions\Api\V1\Form\DeleteFormFieldAction;
use App\Http\Actions\Api\V1\Form\GetFormAction;
use App\Http\Actions\Api\V1\Form\GetFormsAction;
use App\Http\Actions\Api\V1\Form\GetPublicFormAction;
use App\Http\Actions\Api\V1\Form\ReorderFormFieldsAction;
use App\Http\Actions\Api\V1\Form\SubmitFormAction;
use App\Http\Actions\Api\V1\Form\UpdateFormAction;
use App\Http\Actions\Api\V1\Form\UpdateFormFieldAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(Request $request, GetFormsAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function show(int $id, GetFormAction $action): JsonResponse
    {
        return $action->handle($id);
    }

    public function update(Request $request, int $id, UpdateFormAction $action): JsonResponse
    {
        return $action->handle($request, $id);
    }

    public function addField(Request $request, int $formId, AddFormFieldAction $action): JsonResponse
    {
        return $action->handle($request, $formId);
    }

    public function updateField(Request $request, int $fieldId, UpdateFormFieldAction $action): JsonResponse
    {
        return $action->handle($request, $fieldId);
    }

    public function deleteField(int $fieldId, DeleteFormFieldAction $action): JsonResponse
    {
        return $action->handle($fieldId);
    }

    public function reorderFields(Request $request, int $formId, ReorderFormFieldsAction $action): JsonResponse
    {
        return $action->handle($request, $formId);
    }

    public function publicShow(string $formKey, GetPublicFormAction $action): JsonResponse
    {
        return $action->handle($formKey);
    }

    public function submit(Request $request, string $formKey, SubmitFormAction $action): JsonResponse
    {
        return $action->handle($request, $formKey);
    }
}
