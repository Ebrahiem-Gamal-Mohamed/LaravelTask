<?php

namespace App;
use App\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use Notifiable;

    protected $hidden = ['location',];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'user_image',
    ];

    public function user_emp(){
        return $this->hasOne('App\User', 'id');
    }

    /**
     * The attributes that hold geometrical data.
     *
     * @var array
     */
    protected $geometry = array();

    /**
     * Select geometrical attributes as text from database.
     *
     * @var bool
     */
    protected $geometryAsText = false;

    /**
     * Get a new query builder for the model's table.
     * Manipulate in case we need to convert geometrical fields to text.
     *
     * @param  bool  $excludeDeleted
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($excludeDeleted = true)
    {
        if (!empty($this->geometry) && $this->geometryAsText === true)
        {
            $raw = '';
            foreach ($this->geometry as $column)
            {
                $raw .= 'AsText(`' . $this->table . '`.`' . $column . '`) as `' . $column . '`, ';
            }
            $raw = substr($raw, 0, -2);
            return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
        }
        return parent::newQuery($excludeDeleted);
    }

}
