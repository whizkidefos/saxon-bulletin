module.exports = {
    extends: [
      'stylelint-config-standard-scss',
      'stylelint-config-prettier-scss'
    ],
    rules: {
      'at-rule-no-unknown': null,
      'scss/at-rule-no-unknown': true,
      'no-descending-specificity': null,
      'declaration-empty-line-before': null
    }
  };
  
  // postcss.config.js
  module.exports = {
    plugins: [
      require('autoprefixer'),
      require('cssnano')({
        preset: ['default', {
          discardComments: {
            removeAll: true
          }
        }]
      })
    ]
  };