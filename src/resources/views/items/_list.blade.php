{{-- 商品がない場合 --}}
    @if ($items->isEmpty())
        <p>まだ商品は出品されていません。</p>
    @endif

    <div class="item-list" style="display:flex; flex-wrap:wrap; gap:20px;">

        @foreach ($items as $item)
            <div class="item-card" style="width:200px; border:1px solid #ccc; padding:10px;">

                {{-- 商品画像（クリックで詳細へ） --}}
                <div class="item-image">
                    <a href="{{ route('items.show', $item->id) }}">
                        @if ($item->image_path)
                            <img
                                src="{{ asset('storage/' . $item->image_path) }}"
                                alt="{{ $item->title }}"
                                style="width:100%; height:150px; object-fit:cover;"
                            >
                        @else
                            <div style="width:100%; height:150px; background:#eee; display:flex; align-items:center; justify-content:center;">
                                画像なし
                            </div>
                        @endif
                    </a>
                </div>

                {{-- 商品名（クリックで詳細へ） --}}
                <div class="item-name" style="margin-top:8px;">
                    <a href="{{ route('items.show', $item->id) }}">
                        {{ $item->title }}
                    </a>
                </div>

                {{-- 価格 --}}
                <div class="item-price">
                    ¥{{ number_format($item->price) }}
                </div>

            </div>
        @endforeach

    </div>