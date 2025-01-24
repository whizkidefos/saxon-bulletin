module.exports = {
    env: {
      browser: true,
      es2021: true,
      jquery: true
    },
    extends: ['eslint:recommended'],
    parserOptions: {
      ecmaVersion: 12,
      sourceType: 'module'
    },
    rules: {
      'indent': ['error', 2],
      'linebreak-style': ['error', 'unix'],
      'quotes': ['error', 'single'],
      'semi': ['error', 'always'],
      'no-unused-vars': 'warn',
      'no-console': 'warn',
      'prefer-const': 'error',
      'arrow-spacing': 'error',
      'no-var': 'error',
      'object-curly-spacing': ['error', 'always'],
      'array-bracket-spacing': ['error', 'never'],
      'comma-dangle': ['error', 'always-multiline']
    }
  };
