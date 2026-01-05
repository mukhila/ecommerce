<!-- Tab product -->
<div class="title1 section-t-space">
    <h4>Exclusive products</h4>
    <h2 class="title-inner1">Everyday casual</h2>
</div>
<section class="section-b-space pt-0 ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="theme-tab">
                    <ul class="tabs tab-title">
                        <li class="current"><a href="tab-4">DRESSES</a></li>
                        <li><a href="tab-5">TOPS</a></li>
                        <li><a href="tab-6">JEANS</a></li>
                    </ul>
                    <div class="tab-content-cls">
                        <!-- Dresses Tab -->
                        <div id="tab-4" class="tab-content active default">
                            <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
                                @forelse($dresses as $product)
                                    <div>
                                        <x-product-card :product="$product" />
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center">No dresses available at the moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tops Tab -->
                        <div id="tab-5" class="tab-content">
                            <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
                                @forelse($tops as $product)
                                    <div>
                                        <x-product-card :product="$product" />
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center">No tops available at the moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Jeans Tab -->
                        <div id="tab-6" class="tab-content">
                            <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
                                @forelse($winterWear as $product)
                                    <div>
                                        <x-product-card :product="$product" />
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center">No jeans available at the moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Tab product end -->
