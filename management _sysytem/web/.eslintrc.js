module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
    es6: true,
  },
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
      modules: true,
    },
    sourceType: "module",
    parser: "babel-eslint",
  },
  extends: [
    "@nuxtjs",
    "prettier",
    "prettier/vue",
    "plugin:prettier/recommended",
    "plugin:nuxt/recommended",
  ],
  plugins: ["prettier"],
  // add your custom rules here
  rules: {
    "nuxt/no-cjs-in-config": "off",
    "vue/no-use-v-if-with-v-for": [
      "error",
      {
        allowUsingIterationVar: true,
      },
    ],
    "no-unused-vars": ["error", { vars: "all", args: "all" }],
    "padding-line-between-statements": [
      "error",
      {
        blankLine: "any",
        prev: ["const", "let", "var"],
        next: ["const", "let", "var"],
      },
      {
        blankLine: "always",
        prev: "*",
        next: [
          "return",
          "directive",
          "block",
          "block-like",
          "cjs-export",
          "export",
          "function",
          "import",
          "if",
        ],
      },
      {
        blankLine: "always",
        prev: [
          "return",
          "directive",
          "block",
          "block-like",
          "cjs-export",
          "export",
          "function",
          "import",
          "if",
        ],
        next: "*",
      },
      { blankLine: "any", prev: "import", next: "import" },
      { blankLine: "always", prev: "export", next: "export" },
      { blankLine: "always", prev: "cjs-export", next: "cjs-export" },
    ],
    "lines-between-class-members": ["error", "never"],
    "padded-blocks": ["error", { classes: "never" }, { blocks: "never" }],
  },
  overrides: [
    {
      files: ["*.test.js"],
      rules: {
        "no-unused-vars": [
          "error",
          { vars: "all", args: "all", argsIgnorePattern: "^dispatch" },
        ],
      },
    },
  ],

  
};
