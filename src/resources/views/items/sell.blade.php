@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<h1>商品を出品する</h1>

<form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- 商品画像 --}}
    <div>
        <label>商品画像</label>
        <input type="file" name="image" required>
    </div>

    {{-- カテゴリ --}}
    <div>
        <label>カテゴリ</label><br>
        @foreach($categories as $category)
            <label>
                <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                {{ $category->name }}
            </label><br>
        @endforeach
    </div>

    {{-- 商品状態 --}}
    <div>
        <label>商品の状態</label>
        <select name="condition" required>
            <option value="">選択してください</option>
            <option value="新品・未使用">新品・未使用</option>
            <option value="未使用に近い">未使用に近い</option>
            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
            <option value="傷や汚れあり">傷や汚れあり</option>
        </select>
    </div>

    {{-- 商品名 --}}
    <div>
        <label>商品名</label>
        <input type="text" name="title" required>
    </div>

    {{-- ブランド --}}
    <div>
        <label>ブランド</label>
        <input type="text" name="brand">
    </div>

    {{-- 説明 --}}
    <div>
        <label>商品説明</label>
        <textarea name="description" required></textarea>
    </div>

    {{-- 価格 --}}
    <div>
        <label>価格</label>
        <input type="number" name="price" required>
    </div>

    <button type="submit">出品する</button>
</form>
