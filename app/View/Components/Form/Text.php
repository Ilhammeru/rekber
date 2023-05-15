<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Text extends Component
{
    public $error;
    public $id;
    public $name;
    public $text;
    public $className;
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($error, $id, $name, $text, $type = 'text', $className = '')
    {
        $this->error = $error;
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->className = $className;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.text');
    }
}
