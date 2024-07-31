<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaseController
{
    protected $model;
    protected $defaultPerPage = 15;

    public function __construct($model) {
        $this->model = $model;
    }

    public function index(Request $request) {
        $query = $this->model->query();

        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = (int) $request->query('perPage', $this->defaultPerPage);
        if ($perPage <= 0) {
            $perPage = $this->defaultPerPage;
        }
        $items = $query->orderBy('id', 'desc')->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->is_featured = $item->is_featured ? 'Y' : 'N';
            $item->is_video = $this->isVideo('storage/'.$item->image);
            $item->is_mobile_video = $this->isVideo('storage/'.$item->mobile_image);
            return $item;
        });

        return view($this->getViewName('index'), compact('items', 'perPage', 'search'));
    }

    public function isVideo($filePath)
    {
        $videoExtensions = ['mp4', 'webm', 'ogg'];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return in_array($extension, $videoExtensions);
    }

    public function setDefaultPerPage($value) {
        $this->defaultPerPage = $value;
    }

    public function create() {
        return view($this->getViewName('create'));
    }

    public function store(Request $request) {
        $store = $this->handleRequest($request);

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route($this->getRouteName('create'));
        }

        return redirect()->route($this->getRouteName('index'));
    }

    public function edit($id) {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->route($this->getRouteName('index'))
                ->with('error', '해당 게시물을 찾을 수 없습니다.');
        }

        $attributes = ['image', 'mobile_image', 'file'];

        foreach ($attributes as $attribute) {
            $item = $this->addFileInformation($item, $attribute);
        }

        return view($this->getViewName('edit'), compact('item'));
    }

    private function addFileInformation($item, $attribute) {
        if (array_key_exists($attribute, $item->toArray())) {
            $filePath = $item->{$attribute};
            $fileExists = isset($filePath) && Storage::exists('public/' . $filePath);

            if ($fileExists) {
                $originalFileName = pathinfo($filePath, PATHINFO_FILENAME);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $formattedFileName = $originalFileName . '.' . $extension;
                $item->{$attribute . '_name'} = $formattedFileName;
                $item->{$attribute} = asset('storage/' . $filePath);
            } else {
                $item->{$attribute . '_name'} = null;
                $item->{$attribute} = null;
            }
        } else {
            unset($item->{$attribute . '_name'});
            unset($item->{$attribute});
        }

        return $item;
    }

    public function update(Request $request, $id) {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->route($this->getRouteName('index'))
                ->with('error', '해당 항목을 찾을 수 없습니다.');
        }

        $data = $this->handleRequest($request, $item);

        $item->update($data);

        return redirect()->route($this->getRouteName('index'));
    }

    protected function handleRequest(Request $request, $item = null) {
        $data = $request->except(['file', '_token']);

        if (isset($data['content'])) {
            $data['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $data['content']);
        }

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $filePath = $request->file('image')->storeAs('images', $fileName, 'public');
            $data['image'] = $filePath;
        } elseif ($request->input('remove_image') == '1') {
            if ($item->image) {
                Storage::delete('public/' . $item->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('mobile_image')) {
            $fileName = $request->file('mobile_image')->getClientOriginalName();
            $filePath = $request->file('mobile_image')->storeAs('images', $fileName, 'public');
            $data['mobile_image'] = $filePath;
        } elseif ($request->input('remove_image') == '1') {
            if ($item->mobile_image) {
                Storage::delete('public/' . $item->mobile_image);
            }
            $data['mobile_image'] = null;
        }

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $data['file'] = $filePath;
        } elseif ($request->input('remove_file') == '1') {
            if ($item->file) {
                Storage::delete('public/' . $item->file);
            }
            $data['file'] = null;
        }

        $data['is_featured'] = $request->input('is_featured');

        return $data;
    }

    public function delete($item) {
        $item = $this->model->findOrFail($item);
        $item->delete();

        return redirect()->route($this->getRouteName('index'));
    }

    protected function getViewName($view) {
        return 'admin.' . strtolower(class_basename($this->model)) . ucfirst($view);
    }

    protected function getRouteName($route) {
        return 'admin.' . strtolower(class_basename($this->model)) . ucfirst($route);
    }

}
