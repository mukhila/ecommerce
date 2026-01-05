                                <nav id="main-nav">
                                    <div class="toggle-nav"><i class="ri-bar-chart-horizontal-line sidebar-bar"></i>
                                    </div>
                                    <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                        <li class="mobile-box">
                                            <div class="mobile-back text-end">Menu<i class="ri-close-line"></i></div>
                                        </li>
                                        @if(isset($mainMenus))
                                            @foreach($mainMenus as $menu)
                                                <li>
                                                    <a href="{{ $menu->url }}">{{ $menu->name }}</a>
                                                    @if($menu->children->isNotEmpty())
                                                        <ul>
                                                            @foreach($menu->children as $child)
                                                                <li>
                                                                    <a href="{{ $child->url }}">{{ $child->name }}</a>
                                                                    @if($child->children->isNotEmpty())
                                                                        <ul>
                                                                            @foreach($child->children as $subChild)
                                                                                <li><a href="{{ $subChild->url }}">{{ $subChild->name }}</a></li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </nav>
