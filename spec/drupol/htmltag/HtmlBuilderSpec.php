<?php

namespace spec\drupol\htmltag;

use drupol\htmltag\HtmlBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(HtmlBuilder::class);
    }

    public function it_should_create_html()
    {
        $this
            ->p(['class' => ['paragraph']], 'content')
            ->div(['class' => 'container'], 'this is a simple div')
            ->_()
            ->a()
            ->span(['class' => 'link'], 'Link content')
            ->_()
            ->div(['class' => 'Unsecure "classes"'], 'Unsecure <a href="#">content</a>')
            ->__toString()
            ->shouldReturn('<p class="paragraph">content<div class="container">this is a simple div</div></p><a><span class="link">Link content</span></a><div class="Unsecure &quot;classes&quot;">Unsecure &lt;a href=&quot;#&quot;&gt;content&lt;/a&gt;</div>');
    }
}
