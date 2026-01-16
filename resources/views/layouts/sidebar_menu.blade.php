                            <div class="navbar">
                                <a href="#!" onclick="openNav()">
                                    <div class="bar-style"><i class="ri-bar-chart-horizontal-line sidebar-bar"></i>
                                    </div>
                                </a>
                                <div id="mySidenav" class="sidenav">
                                    <a href="#!" class="sidebar-overlay" onclick="closeNav()"></a>
                                    <nav>
                                        <div onclick="closeNav()">
                                            <div class="sidebar-back text-start"><i
                                                    class="ri-arrow-left-s-line pe-2"></i>
                                                Back</div>
                                        </div>
                                        @inject('categoryModel', 'Modules\Product\Models\Category')
                                        @php
                                            $categories = $categoryModel::whereNull('parent_id')
                                                ->where('is_active', true)
                                                ->with('children.children')
                                                ->get();
                                        @endphp
                                        <ul id="sub-menu" class="sm pixelstrap sm-vertical">
                                            @foreach($categories as $category)
                                                <li>
                                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                                                    @if($category->children && $category->children->count() > 0)
                                                        <ul>
                                                            @foreach($category->children as $child)
                                                                <li>
                                                                    <a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a>
                                                                    @if($child->children && $child->children->count() > 0)
                                                                        <ul>
                                                                            @foreach($child->children as $grandChild)
                                                                                <li>
                                                                                    <a href="{{ route('category.show', $grandChild->slug) }}">{{ $grandChild->name }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="brand-logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('frontassets/images/logo.png') }}" class="img-fluid blur-up lazyload" alt="">
                                </a>
                            </div>
