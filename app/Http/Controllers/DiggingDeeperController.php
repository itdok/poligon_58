<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    /**
     *Базовая информация:
     * @url https://laravel.com/docs/6.x/collections
     * Справочная информация:
     * @url https://laravel.com/api/6.x/Illuminate/Support/Collection.html
     * Вариант коллекции для моделей eloquent:
     * @url https://laravel.com/api/6.x/Illuminate/Database/Eloquent/Collection.html
     * Билдер запросов - то с чем можно перепутать коллекции:
     * https://laravel.com/docs/6.x/queries
     */
    public function collections()
    {
        $result = [];

        /** @var /Illuminate/Database/Eloquent/Collection $eloquentCollection */
        $eloquentCollection = BlogPost::withTrashed()->get();

//        dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());
        /**@var /Illuminate/Support/Collection $collection */
        $collection = collect($eloquentCollection->toArray());

//        dd(
//            get_class($eloquentCollection),
//            get_class($collection),
//            $collection
//        );
//        dd($result);

//        $result['first'] = $collection->first();
//        $result['last'] = $collection->last();
//        dd($result);
//        $result['where']['data'] = $collection
//            ->where('category_id', 10)
//            ->values()
//            ->keyBy('id');
//        dd($result);

//        $result['where']['count'] = $result['where']['data']->count();
//        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
//        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();
//        dd($result);

//        //так выборку делать не правильно - нет понимания с чем сравнение -> count
//        if ($result['where']['count']){
//            // ...
//        }
//        // лучше выборку делать так -> isEmpty & isNotEmpty
//        if ($result['where']['data']->isNotEmpty()){
//            // ...
//        }

//        $result['where_first'] = $collection
//            ->firstWhere('created_at', '>', '2020-02-20 18:12:01');
//        dd($result);

    // Базовая переменная не изменится. Просто вернётся изменённая версия.
//        $result['map']['all'] = $collection->map(function (array $item){
//            $newItem = new \stdClass();
//            $newItem->item_id = $item['id'];
//            $newItem->item_name = $item['title'] ;
//            $newItem->exists = is_null($item['deleted_at']);
//
//            return $newItem;
//        });
////        dd($result);
//
//        $result['map']['not_exists'] = $result['map']['all']
//            ->where('exists', '=', false);
//
//        dd($result);

    // Базовая переменная изменится (трансформимруется)
        $collection->transform(function (array $item){
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'] ;
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });
//        dd($collection);
//
//        $newItem = new \stdClass();
//        $newItem->item_id = 9999;
////
//        $newItem2 = new \stdClass();
//        $newItem2->item_id = 8888;
//        dd($newItem, $newItem2);

        // Установить эллемент в начало и в конец коллекции
//        $newItemFirst   = $collection->prepend($newItem);
//        $newItemLast    = $collection->push($newItem2);
//        dd($collection);
//        $newItemFirst = $collection->prepend($newItem)->first();
//        $newItemLast = $collection->push($newItem2)->last();
//        $pulledItem = $collection->pull(1);
//        dd(compact('collection', 'newItemFirst', 'newItemLast', 'pulledItem'));

        // Фильтрация. Замена orWhere()
//        $filtered = $collection->filter(function ($item){
//            $byDey  = $item->created_at->isThursday();
//            $byDate = $item->created_at->day == 20;
////            $result = $item->created_at->isThursday() && ($item->created_at->day == 20);
//            $result = $byDey && $byDate;
//
//            return $result;
//        });
////        dd($filtered);
//        dd(compact('filtered'));

        $sortedSimpleCollection = collect([5, 3, 1, 4])->sort()->values();
        $sortedAscCollection    = $collection->sortBy('created_at')->keyBy('item_id');
        $sortedDescCollection   = $collection->sortByDesc('item_id')->keyBy('item_id');

        dd(compact('sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection'));
    }
}
