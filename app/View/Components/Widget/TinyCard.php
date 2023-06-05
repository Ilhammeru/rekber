<?php

namespace App\View\Components\Widget;

use Illuminate\View\Component;

class TinyCard extends Component
{
    public $icon;
    public $heading;
    public $text;
    public $headingColor;
    public $textColor;
    public $colorIcon;
    public $idHeading;
    public $actionLink;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($icon, $heading, $text = '', $colorIcon, $headingColor = 'text-black', $textColor = 'text-dark-grey', $idHeading = null, $actionLink = null)
    {
        $this->icon = $icon;
        $this->heading = $heading;
        $this->text = $text;
        $this->headingColor = $headingColor;
        $this->textColor = $textColor;
        $this->colorIcon = $colorIcon;
        $this->idHeading = $idHeading;
        $this->actionLink = $actionLink;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.tiny-card');
    }
}
