<?php

namespace App\Filters;

use App\Helpers\Helper;
use Vinkla\Hashids\Facades\Hashids;

class ApiFilter
{
      /**
       *  How to use this filter ?
       *
       *  Ex: we want to apply filetrs on candidate model
       *
       *  $table='candidates';
       *  $model=app(App/Candidate::class);
       *  $extras=[['candidate_id','=',20]];
       *
       *
       * |-------------------------------------------------------------------------------------------------------------|
       * | 1 | required | $filter = new ApiFilterV($model, $request,$extras);                                         |-------------------------------------------------------------------------------------------------------------|
       * | 2 | required |$filter->apply(config('filters.candidate'));                                     |-------------------------------------------------------------------------------------------------------------|
       * | 3 | optional | $filter->applyAdvancedFilter(config('filters.candidate_has'));  // if model has custom filters                                |
       * |-------------------------------------------------------------------------------------------------------------|
       * | 4 | required | $query = $filter->getData(); // here collection will be returned                          |
       * |-------------------------------------------------------------------------------------------------------------|
       *
       *  Above all steps are not required , some of them are optional
       *  In every functions all arguments are not required ther is some default value for optional arguments.
       *  More details added in every function description.
       */



    /**
     * @var \App\Model
     */
    private $model;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var array
     */
    private $extras;


    public function __construct($model, $request, $extras = null)
    {
        $this->model           = $model;
        $this->request         = $request;
        $this->extras          = $extras;
        $this->matches         = [];
        $this->advancedFilter  = [];
        $this->associate_array = [];
    }


    /**
     * Apply filters on given column names.
     *
     * @return string Encoded url
     */
    public function apply($names)
    {
        foreach ($names as $name => $filter) {
            if (empty($this->request->$name) === false) {
                if ($filter === 'like') {
                    array_push($this->matches, [$name, 'like', '%' . $this->request->$name . '%']);
                } else {
                    array_push($this->matches, [$name, $filter, $this->request->$name]);
                }

                $this->associate_array[$name] = $this->request->$name;
            }
        }

        // apply given extras
        if (empty($this->extras) === false) {
            foreach ($this->extras as $name => $value) {
                array_push($this->matches, [$name, '=', $value]);
            }
        }
    }


    public function applyAdvancedFilter($names)
    {
        foreach ($names as $name => $filter) {
            $param = $filter['param'];
            if (empty($this->request->$param) === false) {
                $keys = explode('.', $name);
                try {
                    if (array_key_exists("decode", $filter)) {
                        $value = Hashids::connection($filter['decoder'])->decode($this->request->$param)[0];
                    } else {
                        $value = $this->request->$param;
                    }

                    array_push(
                        $this->advancedFilter,
                        [
                            'collection' => $keys[0],
                            'value'      => $value,
                            'field'      => $keys[1],
                            'operand'    => $filter['operand'],
                        ]
                    );
                    $this->associate_array[$param] = $this->request->$name;
                } catch (\Exception $ex) {
                    // key is invalid
                    warning(
                        'Invalid key',
                        [
                            'collection' => $keys[0],
                            'value'      => $this->request->$param,
                            'field'      => $keys[1],
                        ]
                    );
                }//end try
            }//end if
        }//end foreach
    }


    /**
     * Generate URL-encoded query string from filter array
     *
     * @return string Encoded url
     */
    public function getUrlString()
    {
        return http_build_query($this->associate_array);
    }


    /**
     * Get filtered data
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getData()
    {

        $url  = Helper::buildPageUrl($this->request);
        $url .= (strrpos($url, '?') ? '&' : '?') . $this->getUrlString();

        if (empty($this->advancedFilter) === false) {
            $query = $this->model;
            foreach ($this->advancedFilter as $filter) {
                $query = $query->whereHas(
                    $filter['collection'],
                    function ($subquery) use ($filter) {
                        if ($filter['operand'] === "like") {
                            $subquery->where($filter['field'], "like", "%" . $filter['value'] . "%");
                        } else {
                            $subquery->where($filter['field'], $filter['operand'], $filter['value']);
                        }
                    }
                );
            }
        }

        if (empty($this->matches) === false) {
            $query = isset($query) ? $query : $this->model;
            $query = $query->where($this->matches);
        }

        $query = isset($query) ? $query : $this->model;
        $query = $query->paginate(($this->request->per_page) ? (int) $this->request->per_page : 15)
            ->withPath($url);
        info('query', ['query' => $query]);
        return $query;
    }
}
