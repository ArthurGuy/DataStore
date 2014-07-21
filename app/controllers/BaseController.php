<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public function ifBrowser(Closure $ifBrowser, Closure $ifElse)
    {
        $wants = \Request::getAcceptableContentTypes();
        if (count($wants) > 0)
        {
            if (in_array('text/html', $wants))
            {
                return $ifBrowser();
            }
        }
        return $ifElse();
    }
}
