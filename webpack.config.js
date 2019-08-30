const path = require('path');
const webpack = require("webpack");
const htmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');// 分离 css
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');// 主要用于压缩/去重
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const HappyPack = require('happypack');
const os = require('os');
const happyThreadPool = HappyPack.ThreadPool({ size: os.cpus().length });
const argv = require('minimist')(process.argv.slice(2));
// console.log(argv.env);

const htmlTitle = '车亿百后台系统';
const iconPath = 'src/assets/icon/favicon.ico';
const fontPath = '/static/admin/css/font-awesome.min.css';// 字体文件路径
const commonPath = '/static/admin/common/common.js';// 公共函数文件路径
const envPath = '/static/admin/common/env.js';// 公共变量文件路径
const m = 'admin';


// 不需要打包的import引入的 npm 包
const vendor = {
  	// 'vue': 'Vue',
  	// 'element-ui': 'ElementUI',
};
function resolve(src=''){return path.join(__dirname, 'public/static/'+src)}
const plugins = [
    new htmlWebpackPlugin({
        template: resolve('src/index.html'),
        filename: 'index.html',
        title:htmlTitle,
        favicon:resolve(iconPath),
        minify:{
            collapseWhitespace: true,
            removeComments: true,
        },
        inject:true,
        fontPath:fontPath,
        common:commonPath,
        env:envPath
    }),
    new webpack.ProgressPlugin(),
    new MiniCssExtractPlugin({
        filename: 'css/[name].css',
        chunkFilename:'css/[name].css'
    }),
    new webpack.HashedModuleIdsPlugin(),
    new webpack.HotModuleReplacementPlugin(),// 热更新
    new VueLoaderPlugin(),
    new CopyWebpackPlugin([{// 字体文件
        from: resolve('src/assets/fonts'), // 不打包直接输出的文件
        to: resolve(m), // 打包后静态文件放置位置-
        ignore: ['.css'] // 忽略规则。（这种写法表示将该文件夹下的所有文件都复制）
    }]),
    new CopyWebpackPlugin([{// 公共函数文件
        from: resolve('src/common/common.js'), // 不打包直接输出的文件
        to: resolve(m + '/common'), // 打包后静态文件放置位置
    }]),
    new CopyWebpackPlugin([{// 公共变量文件
        from: resolve('src/common/env.js'), // 不打包直接输出的文件
        to: resolve(m + '/common'), // 打包后静态文件放置位置
    }]),
    new HappyPack({
        id: 'happybabelJs',
        loaders: [{loader:'babel-loader',options:{cacheDirectory:true}}],
        threadPool: happyThreadPool,
        verbose: true
    }),
    new HappyPack({
        id: 'happybabelCss',
        loaders: [
            {loader:'css-loader'/*,options:{minimize:true,cacheDirectory:true}*/}
        ],
        threadPool: happyThreadPool,
        verbose: true
    })
];
// 设置清除的目录
(argv.env == 'production')&&(plugins.push(new CleanWebpackPlugin()));
module.exports = {
    entry: {
    	main:resolve('src/main.js')
    },
    output: {
        path: resolve(m),
        chunkFilename: 'js/[name].js',
        filename: 'js/[name].js',
        publicPath: '/static/'+ m +'/'
    },
    externals: vendor,
    // devtool: 'cheap-module-eval-source-map',// cheap-module-source-map
    devServer: {
        contentBase: resolve(m),
        host: '127.0.0.1',
        port: 3000,
        headers: {
            'Access-Control-Allow-Origin': '*'
        },
        hot: true,
        inline: true,
        compress:true,
    },

    performance: {
        hints: "warning", // 枚举
        maxAssetSize: 30000000, // 整数类型（以字节为单位）
        maxEntrypointSize: 50000000, // 整数类型（以字节为单位）
        assetFilter: function(assetFilename) {
            // 提供资源文件名的断言函数
            return assetFilename.endsWith('.css') || assetFilename.endsWith('.js');
        }
    },
    module: {
        rules: [{
            test: /\.css$/,
            use:[MiniCssExtractPlugin.loader,'css-loader',
            	{loader:'postcss-loader',options:{
            		indet:'postcss',
            		sourceMap: true,
            		plugins:[require('autoprefixer')({
        				overrideBrowserslist : ['last 100 versions'] //必须设置支持的浏览器才会自动添加添加浏览器兼容})
            		})]
            }}]
        }, {
            test: /\.less$/,
            use:['style-loader','happypack/loader?id=happybabelCss',
                {loader:'postcss-loader',options:{
                    indet:'postcss',
                    sourceMap: true,
                    plugins:[require('autoprefixer')({
                        overrideBrowserslist : ['last 100 versions']
                    })]
            }},'less-loader']
        }, {
            test: /\.(scss|sass)$/,
            use:['style-loader','happypack/loader?id=happybabelCss',
                {loader:'postcss-loader',options:{
                        indet:'postcss',
                        sourceMap: true,
                        plugins:[require('autoprefixer')({
                            overrideBrowserslist : ['last 100 versions']
                        })]
            }},'sass-loader']
        }, {
            test: /\.styl(us)$/,
            use: ['style-loader', 'happypack/loader?id=happybabelCss',
                {loader:'postcss-loader',options:{
                    indet:'postcss',
                    sourceMap: true,
                    plugins:[require('autoprefixer')({
                        overrideBrowserslist : ['last 100 versions']
                    })]
            }},'stylus-loader']
        }, {
            test: /\.(jpg|png|gif|bmp|jpeg)(\?.*)?$/i,
            // use:['url-loader?limit=7631&name=images/[hash:8]-[name].[ext]']
            use: ['url-loader?limit=7631&name=images/[hash:8]-[name].[ext]',{
            	loader:'image-webpack-loader',
            	options:{bypassOnDebug: true}
            }]
        }, {
            test: /\.(ttf|eot|svg|woff|woff2)(\?.*)?$/,
            use: 'url-loader?limit=10000&name=fonts/[hash:8]-[name].[ext]'
        }, {
            test: /\.(js|jsx)$/,
            use: 'happypack/loader?id=happybabelJs',
            include: resolve('src'),
            exclude: '/node_modules/'
        }, {
            test: /\.vue$/,
            use: 'vue-loader'
        }, /*{
          test: /\.tsx?$/,
          use: 'ts-loader',
          exclude: /node_modules/
        }, /*{ // html中使用图片
        	test: /\.(html|htm)$/,
        	use: [{loader:'html-withimg-loader',options:{minimize: true}}]
        }*/]
    },
    plugins:plugins,
    optimization: {
	    runtimeChunk: {
	      	name: 'manifest'
	    },
	    minimizer: [
            new UglifyJsPlugin({
                cache: true,//启动缓存
                parallel: true,//启动并行压缩
                //如果为true的话，可以获得sourcemap
                sourceMap: true // set to true if you want JS source maps
            }),
            //压缩css资源的
            new OptimizeCSSAssetsPlugin({
            	assetNameRegExp: /\.css$/g,
                cssProcessor: require('cssnano'),
                // cssProcessorOptions: cssnanoOptions,// cssProcessorOptions可能没起作用，则只需要指定cssProcessorPluginOptions
                cssProcessorPluginOptions: {
					preset: ['default', {
				        discardComments: {
				            removeAll: true,// 对注释的处理
				        },
				        normalizeUnicode: false// 建议false,否则在使用unicode-range的时候会产生乱码
				    }]
				 },
                canPrint: true// 是否打印处理过程中的日志
            })
	    ], // [new UglifyJsPlugin({...})]
	    splitChunks:{
	      	chunks: 'async',
	      	minSize: 30000,
	      	minChunks: 1,
	      	maxAsyncRequests: 5,
	      	maxInitialRequests: 3,
	      	name: false,
	      	cacheGroups: {
		        vendor: {
		          name: 'vendor',
		          chunks: 'initial',
		          priority: -10,
		          reuseExistingChunk: false,
		          test: /node_modules\/(.*)\.js/
		        },
		        styles: {
		          name: 'styles',
		          test: /\.(scss|css)$/,
		          chunks: 'all',
		          minChunks: 1,
		          reuseExistingChunk: true,
		          enforce: true
		        },
		        commons: {
                    name: "commons",
                    chunks: "initial",
                    minChunks: 2
                }
	      	}
	    }
	},
    resolve: {
    	// 数组内填入什么后缀，引入该后缀时可以文件名可以不带后缀
        extensions: ['.tsx','.ts','.js','.vue','.json'],
        alias: {
            "vue$": "vue/dist/vue.js",
            "@": resolve('src')
        }
    }
};