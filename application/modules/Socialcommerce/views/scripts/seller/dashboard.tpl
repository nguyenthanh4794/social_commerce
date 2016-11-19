.scstall-my-store-page{
    .scstall-items{
        display: flex;
        flex-flow: wrap;
        margin: 0 -10px;

        .scstall-item{
            width: 50%;
            padding: 0 10px;
            margin-bottom: 20px;

            @media (max-width: 991px){
                width: 100%;
            }

            .scstall-item-content{
                border: 1px solid @border-item;
                padding: 15px;
                position: relative;
                .clearfix;

                .scstall-bg{
                    width: 120px;
                    height: 120px;
                    .yn-background-image(contain,block);
                    border: 1px solid @border-rgba;
                    background-origin: border-box;
                    float: left;
                    margin-right: 10px;
                    position: relative;
                    margin-bottom: 30px;

                    .scstall-status-block{
                        bottom: -13px;
                        top: auto;
                        left: 50%;
                        transform: translate(-50%,0);
                    }

                    @media (max-width: 480px){
                        float: none;
                        margin: auto;
                        margin-bottom: 25px;
                    }
                }

                .scstall-info{
                    width: ~"calc(100% - 130px)";
                    float: right;

                    @media (max-width: 480px){
                        width: 100%;
                        float: none;
                    }

                    .scstall-title{
                        font-weight: 700;
                        .yn-truncate(block);
                        position: relative;
                        top: -2px;
                    }

                    .scstall-info-detail{
                        color: @gray-dark;
                        font-size: 12px;
                    }

                    .scstall-categories{
                        border-bottom: 1px solid @border-color;
                        padding-bottom: 5px;
                        margin-bottom: 10px;
                    }

                    .scstall-package-product{
                        display: flex;

                        span{
                            flex: 1;
                            font-size: 14px;
                            max-height: 60px;
                            overflow: hidden;
                            line-height: 20px;

                            &:first-of-type{
                                padding-right: 15px;
                                flex: 1.5;
                            }

                            label{
                                display: block;
                                font-size: 12px;
                                color: @gray-dark;
                                text-transform: uppercase;
                                font-size: 12px;
                                font-weight: 400;
                                margin-bottom: 0;
                            }
                        }
                    }
                }

                .scstall-statistic-block{
                    margin: 30px -15px -15px -15px;
                    border-top: 1px solid @border-rgba;
                }

                .scstall-featured{
                    font-size: 12px;
                    color: #FFF;
                    position: absolute;
                    right: -1px;
                    top: -1px;
                    z-index: 1;

                    .scstall-featured-triangle{
                        border-top: 40px solid #ffa800;
                        border-left: 40px solid transparent;
                        width: 0;
                        height: 0;
                        display: block;

                        .ynicon {
                            position: absolute;
                            right: 5px;
                            top: 6px;
                        }
                    }
                }

                .scstall-actions-block{
                    right: auto;
                    left: -1px;
                    top: -1px;

                    .dropdown{
                        margin-left: 0;
                    }

                    .dropdown-menu.dropdown-menu-right{
                        right: auto;
                        left: 0;
                    }
                }

                &:hover{
                    .scstall-actions-block .dropdown{
                        opacity: 1;
                    }
                }

                .scstall-statistic-block{
                    clear: both;
                    height: 65px;
                    display: flex;
                    align-items: center;
                    .yn-transition;
                    position: relative;

                    &:hover{
                        background-color: @yn-lighter-background;
                    }

                    .scstall-statistic{
                        text-align: center;
                        text-transform: uppercase;
                        color: @gray-dark;
                        font-size: 11px;
                        padding: 0 18px;

                        b{
                            display: block;
                            font-weight: 700;
                            color: @text-color;
                            font-size: 16px;
                            margin-bottom: 3px;
                        }
                    }

                    .scstall-statistic-dropdown{
                        position: absolute;
                        right: 0;
                        top: 0;

                        .scstall-statistic-dropdown-btn{
                            width: 55px;
                            height: 64px;
                            display: block;
                            cursor: pointer;
                            font-size: 24px;
                            color: @gray-dark;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            .yn-transition;

                            &:before{
                                content: "\ea17";
                                font-family: 'ynicon' !important;
                                speak: none;
                                font-style: normal;
                                font-weight: normal;
                                font-variant: normal;
                                text-transform: none;
                                line-height: 1;
                                -webkit-font-smoothing: antialiased;
                                -moz-osx-font-smoothing: grayscale;
                            }

                            &:hover{
                                background-color: #eee;

                                &:before{
                                    content: "\ea18";
                                }
                            }
                        }

                        &.open{
                            .dropdown-menu{
                                top: auto;
                                bottom: 100%;
                                right: -1px;
                                left: auto;
                                display: flex;
                                height: 71px;
                                align-items: center;

                                &:before,
                                &:after{
                                    top: 100%;
                                    right: 15px;
                                    border: solid transparent;
                                    content: " ";
                                    height: 0;
                                    width: 0;
                                    position: absolute;
                                    pointer-events: none;
                                }

                                &:before{
                                    border-color: rgba(136, 136, 136, 0);
                                    border-top-color: #888;
                                    border-width: 11px;
                                    margin-right: -1px;
                                }

                                &:after{
                                    border-color: rgba(255, 255, 255, 0);
                                    border-top-color: #fff;
                                    border-width: 10px;
                                }

                            }

                            .scstall-statistic-dropdown-btn{
                                background-color: #eee;

                                &:before{
                                    content: "\ea18";
                                }
                            }
                        }

                        .dropdown-menu{
                            .scstall-statistic{
                                padding: 0 20px;
                            }

                            .scstall-flag{display: none;}
                        }
                    }

                    @media (max-width: 1100px){
                        .scstall-flag{
                            &.scstall-views{
                                display: none;
                            }
                        }

                        .scstall-statistic-dropdown .dropdown-menu{
                            .scstall-flag{
                                &.scstall-views{
                                    display: block;
                                }
                            }
                        }
                    }

                    @media (max-width: 480px){
                        .scstall-flag{
                            display: none;
                        }

                        .scstall-statistic-dropdown .dropdown-menu{
                            height: auto !important;
                            flex-flow: wrap;

                            .scstall-flag{
                                display: block;
                            }
                            .scstall-statistic{
                                padding: 15px 0;
                                width: 50%;
                            }
                        }
                    }
                }
            }
        }
    }
}