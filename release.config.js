const config = {
  branches: ['main'],
  plugins: [
    [
      '@semantic-release/commit-analyzer',
      {
        parserOpts: {
          headerPattern: /^(\w+):"(.*)"$/,
          headerCorrespondence: ['type', 'subject']
        }
      }
    ],
    '@semantic-release/release-notes-generator',
    '@semantic-release/npm',
    [
      '@semantic-release/git',
      {
        assets: ['dist/**/*', 'package.json', 'docs/CHANGELOG.md'],
        message: 'chore(release): ${nextRelease.version} [skip ci]\n\n${nextRelease.notes}'
      }
    ],
    '@semantic-release/github'
  ]
};

module.exports = config;
