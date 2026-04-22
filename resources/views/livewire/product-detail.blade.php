<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
    @php $mediaItems = $product->getMedia('images'); @endphp

    <!-- Image Gallery -->
    <div x-data="{
            images: {{ json_encode($mediaItems->map->getUrl()->values()) }},
            current: 0,
            go(i) {
                this.current = i;
                const lensImg = document.getElementById('zoom-lens-img');
                const baseImg = document.getElementById('zoom-image');
                if (lensImg) lensImg.src = this.images[i];
                if (baseImg) baseImg.src = this.images[i];
                document.getElementById('zoom-lens')?.classList.remove('visible');
            },
            prev() { if (this.current > 0) this.go(this.current - 1); },
            next() { if (this.current < this.images.length - 1) this.go(this.current + 1); },
         }" class="flex flex-col gap-3">

        <!-- Main image + arrows -->
        <div class="relative bg-white border border-gray-100 rounded-2xl h-96 lg:h-[500px] flex items-center justify-center overflow-hidden select-none"
             id="zoom-container">

            @if($mediaItems->isNotEmpty())
                <img :src="images[current]"
                     src="{{ $mediaItems->first()->getUrl() }}"
                     alt="{{ $product->name }}"
                     id="zoom-image"
                     class="w-full h-full object-contain rounded-2xl p-4"
                     draggable="false">

                <!-- Liquid Glass Lens -->
                <div id="zoom-lens" class="pointer-events-none absolute hidden" style="width:180px;height:180px;">
                    <div class="absolute inset-0 rounded-full"
                         style="background:radial-gradient(circle at 38% 35%,rgba(255,255,255,0.55) 0%,rgba(255,255,255,0.18) 45%,rgba(255,255,255,0.08) 100%);border:1.5px solid rgba(255,255,255,0.75);box-shadow:0 0 0 1px rgba(0,0,0,0.06),0 8px 32px rgba(0,0,0,0.18),inset 0 1px 0 rgba(255,255,255,0.9),inset 0 -1px 0 rgba(0,0,0,0.08);"></div>
                    <div class="absolute inset-0 rounded-full overflow-hidden">
                        <img id="zoom-lens-img"
                             src="{{ $mediaItems->first()->getUrl() }}"
                             alt="" class="absolute max-w-none" draggable="false"
                             style="width:540px;height:540px;object-fit:cover;">
                    </div>
                    <div class="absolute pointer-events-none rounded-full"
                         style="top:10%;left:15%;width:55%;height:30%;background:radial-gradient(ellipse at 40% 40%,rgba(255,255,255,0.70) 0%,rgba(255,255,255,0.0) 100%);filter:blur(4px);"></div>
                    <div class="absolute pointer-events-none rounded-full"
                         style="bottom:5%;left:20%;width:60%;height:20%;background:radial-gradient(ellipse,rgba(0,0,0,0.12) 0%,transparent 100%);filter:blur(3px);"></div>
                </div>

                <!-- Prev arrow -->
                <button x-show="images.length > 1" @click="prev" :disabled="current === 0"
                        class="z-10 flex items-center justify-center rounded-full transition-all"
                        style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 56px; height: 56px; background-color: rgba(0,0,0,0.75); box-shadow: 0 0 0 4px rgba(255,255,255,0.4), 0 8px 24px rgba(0,0,0,0.5);"
                        :class="current === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:scale-110'">
                    <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Next arrow -->
                <button x-show="images.length > 1" @click="next" :disabled="current === images.length - 1"
                        class="z-10 flex items-center justify-center rounded-full transition-all"
                        style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 56px; height: 56px; background-color: rgba(0,0,0,0.75); box-shadow: 0 0 0 4px rgba(255,255,255,0.4), 0 8px 24px rgba(0,0,0,0.5);"
                        :class="current === images.length - 1 ? 'opacity-30 cursor-not-allowed' : 'hover:scale-110'">
                    <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <svg class="size-32 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            @endif
        </div>

        <!-- Thumbnails -->
        @if($mediaItems->count() > 1)
            <div class="flex gap-2">
                @foreach($mediaItems as $i => $media)
                    <button @click="go({{ $i }})"
                            class="w-20 h-20 flex-shrink-0 rounded-xl overflow-hidden border-2 transition-all"
                            :class="{{ $i }} === current ? 'border-blue-500 ring-2 ring-blue-300' : 'border-transparent hover:border-gray-300'">
                        <img src="{{ $media->getUrl() }}" alt="{{ $product->name }} {{ $i + 1 }}"
                             class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    @if($mediaItems->isNotEmpty())
    @push('head')
    <style>
        #zoom-container { cursor: none; }
        #zoom-lens { transition: opacity 0.15s ease; }
        #zoom-lens.visible { display: block !important; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('zoom-container');
        const lens      = document.getElementById('zoom-lens');
        const lensImg   = document.getElementById('zoom-lens-img');
        const baseImg   = document.getElementById('zoom-image');
        if (!container || !lens || !lensImg || !baseImg) return;

        const LENS_SIZE = 180;
        const ZOOM      = 5;
        const HALF      = LENS_SIZE / 2;

        function move(e) {
            const rect = container.getBoundingClientRect();
            const cx   = (e.clientX ?? e.touches?.[0]?.clientX) - rect.left;
            const cy   = (e.clientY ?? e.touches?.[0]?.clientY) - rect.top;

            const lx = Math.max(HALF, Math.min(rect.width  - HALF, cx));
            const ly = Math.max(HALF, Math.min(rect.height - HALF, cy));

            lens.style.left = (lx - HALF) + 'px';
            lens.style.top  = (ly - HALF) + 'px';
            lens.classList.add('visible');

            const zoomedW = LENS_SIZE * ZOOM;
            const zoomedH = LENS_SIZE * ZOOM;
            lensImg.style.width  = zoomedW + 'px';
            lensImg.style.height = zoomedH + 'px';

            const px = cx / baseImg.offsetWidth;
            const py = cy / baseImg.offsetHeight;
            lensImg.style.left = -(px * zoomedW - HALF) + 'px';
            lensImg.style.top  = -(py * zoomedH - HALF) + 'px';
        }

        container.addEventListener('mousemove',  move);
        container.addEventListener('mouseleave', () => lens.classList.remove('visible'));
        container.addEventListener('touchmove',  move, { passive: true });
        container.addEventListener('touchend',   () => lens.classList.remove('visible'));
    });
    </script>
    @endpush
    @endif

    <!-- Product Info - BLUF: Key info first for AI -->
    <article class="flex flex-col">
        <header>
            <p class="text-sm text-gray-500 font-medium">SKU: {{ $product->sku }}</p>
            <h1 class="mt-2 text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
            <p class="mt-4 text-3xl font-bold">$ {{ number_format($product->price, 2) }}</p>
        </header>

        <!-- BLUF: Key purchase info first -->
        <section class="mt-6" aria-labelledby="purchase-heading">
            <h2 id="purchase-heading" class="sr-only">Purchase Options</h2>

            <!-- Quantity -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                <div class="flex items-center gap-3">
                    <button wire:click="decrementQty"
                            class="size-10 inline-flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors font-bold text-lg">
                        −
                    </button>
                    <span class="w-12 text-center text-lg font-semibold text-gray-900">{{ $quantity }}</span>
                    <button wire:click="incrementQty"
                            class="size-10 inline-flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors font-bold text-lg">
                        +
                    </button>
                </div>
            </div>

            <!-- Add to Cart -->
            <div class="mt-4 flex gap-3">
                <button wire:click="addToCart"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3.5 px-6 inline-flex justify-center items-center gap-2 text-sm font-semibold rounded-xl transition-all duration-300
                            {{ $added ? 'bg-green-500 text-white' : 'bg-gray-900 text-white hover:bg-blue-600' }}">
                    @if($added)
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Added to Cart!
                    @else
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Add to Cart
                    @endif
                </button>
                <a href="/cart"
                   class="py-3.5 px-5 inline-flex items-center justify-center text-sm font-semibold rounded-xl border-2 border-gray-200 text-gray-700 hover:border-blue-600 hover:text-blue-600 transition-colors">
                    View Cart
                </a>
            </div>

            <!-- Shipping info -->
            <div class="mt-4 p-4 bg-gray-50 rounded-xl flex items-start gap-3">
                <svg class="shrink-0 size-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Free shipping on orders over $200</p>
                    <p class="text-xs text-gray-500 mt-0.5">Estimated delivery: 3–5 business days</p>
                </div>
            </div>
        </section>

        <!-- Product Description -->
        @if($product->description)
            <section class="mt-8" aria-labelledby="description-heading">
                <h2 id="description-heading" class="text-lg font-semibold text-gray-900 mb-3">Product Description</h2>
                <div class="prose prose-sm text-gray-600 max-w-none">
                    {!! $product->description !!}
                </div>
            </section>
        @endif

        <!-- Specifications Table -->
        @if(!empty($product->specifications))
            <section class="mt-8">
                <x-product-specs-table :specs="$product->specifications" />
            </section>
        @endif

        <!-- FAQs -->
        @if(!empty($product->faqs))
            <section class="mt-8">
                <x-product-faqs :faqs="$product->faqs" />
            </section>
        @endif
    </article>
</div>
