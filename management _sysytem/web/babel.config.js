module.exports = {
  env: {
    test: {
      presets: [
        [
          "@babel/preset-env",
          {
            targets: {
              node: "current",
            },
          },
        ],
      ],
      plugins: ["babel-plugin-transform-dynamic-import"],
    },
  },
  plugins: ["@babel/syntax-dynamic-import"],
  presets: ["@vue/babel-preset-jsx"],
};
