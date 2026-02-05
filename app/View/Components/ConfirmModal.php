<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ConfirmModal extends Component
{
    public string $id;
    public string $title;
    public string $message;
    public string $confirmText;
    public string $cancelText;

    public function __construct(
        string $id = 'confirm-modal',
        string $title = '¿Estás seguro?',
        string $message = '¿Seguro que deseas eliminar este registro?',
        string $confirmText = 'Eliminar',
        string $cancelText = 'Cancelar'
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->message = $message;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
    }

    public function render()
    {
        return view('components.confirm-modal');
    }
}
