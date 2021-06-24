<template>
  <div class="imprs-container">
    <div class="imprs-container-main">
      <div class="imprs-container-main-hdr" @click="show">
        <!-- Title -->
        <div>
          <b v-if="isCode">Code ({{ data.language }})</b>
          <b v-if="isChallenge">Challenge</b>
          <b v-if="isTest">{{ title }}</b>
        </div>
        <!-- Arrow UP-DOWN -->
        <div v-if="!isVisible">
          <svg
            _ngcontent-tif-c11=""
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
          >
            <path _ngcontent-tif-c11="" fill="none" d="M0 0h24v24H0V0z"></path>
            <path _ngcontent-tif-c11="" d="M7 10l5 5 5-5H7z"></path>
          </svg>
        </div>
        <div v-if="isVisible" style="transform: rotate(180deg)">
          <svg
            _ngcontent-tif-c11=""
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
          >
            <path _ngcontent-tif-c11="" fill="none" d="M0 0h24v24H0V0z"></path>
            <path _ngcontent-tif-c11="" d="M7 10l5 5 5-5H7z"></path>
          </svg>
        </div>
      </div>
      <div v-if="isVisible" class="imprs-container-main-data">
        <div v-if="isCode">
          <pre>{{ data.code }}</pre>
        </div>
        <div v-if="isChallenge" style="background: #f6f9fc; padding: 12px">
          <div class="font-weight-bold" style="font-size: 18px">
            {{ data.title }}
          </div>
          <!-- eslint-disable -->
          <div v-html="description"></div>
        </div>
        <div v-if="isTest">
          <div>
            {{ miniDescription }}
          </div>
          <div class="mt-2">
            <div>
              <div class="font-weight-bold" style="padding: 4px 0">
                Expected
              </div>
              <pre
                style="
                  padding: 12px;
                  border: 1px solid rgb(0 0 0 / 15%);
                  border-radius: 4px;
                "
                >{{ data.expected }}</pre
              >
            </div>
            <div>
              <div class="font-weight-bold" style="padding: 4px 0">Actual</div>
              <pre
                style="
                  padding: 12px;
                  border: 1px solid rgb(0 0 0 / 15%);
                  border-radius: 4px;
                "
                >{{ actual }}</pre
              >
            </div>
            <div>
              <div class="font-weight-bold" style="padding: 4px 0">
                Standard Input
              </div>
              <pre
                style="
                  padding: 12px;
                  border: 1px solid rgb(0 0 0 / 15%);
                  border-radius: 4px;
                "
                >{{ data.inputs }}</pre
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import marked from "marked";

export default {
  name: "CodeCollapse",
  // eslint-disable-next-line vue/require-prop-types
  props: ["isTest", "isCode", "isChallenge", "data", "index"],
  data() {
    return {
      isVisible: false,
      description: marked(this.data.description || "Loading.."),
      title: "",
      miniDescription: "",
      actual: "",
    };
  },
  mounted() {
    if (this.isChallenge) this.description = marked(this.data.description);

    if (this.isTest) {
      // Test is passed
      if (this.data.matches) {
        this.title = `Test #${this.index} - Passed`;
        this.miniDescription = "Program works correctly!";
        this.actual = this.data.message;
      } else {
        // Test is Failed
        this.title = `Test #${this.index} - Failed`;

        if (this.data.hasError) {
          this.miniDescription = "Failed due to errors in your program!";
          this.actual = this.data.errorMessage;
        } else if (!this.data.hasError && !this.data.outOfResources) {
          this.miniDescription = "Failed due to mis-match of expected output!";
          this.actual = this.data.message;
        } else {
          this.miniDescription =
            "Failed due to out of resource and time taken by the program!";
          this.actual = "Program terminated due to out of resources!";
        }
      }
    }
  },
  methods: {
    show() {
      this.isVisible = !this.isVisible;
    },
  },
};
</script>

<style>
.imprs-container {
  width: 100%;
  height: 100%;
  border-radius: 4px;
  background: #fff;
  z-index: 10;
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.1);
  margin-top: 12px;
}

.imprs-container .imprs-container-main {
  width: 100%;
  padding: 0 12px;
}

.imprs-container .imprs-container-main .imprs-container-main-hdr {
  border-top: 1px solid rgba(0, 0, 0, 0.06);
  padding: 0 8px;
  align-items: center;
  align-self: stretch;
  box-sizing: border-box;
  cursor: pointer;
  display: flex;
  flex: 1;
  font-family: inherit;
  font-size: 100%;
  line-height: 154%;
  margin: 0;
  min-height: 48px;
  outline: 0;
  width: 100%;
  justify-content: space-between;
}

.imprs-container .imprs-container-main .imprs-container-main-data {
  width: 100%;
  display: block;
  padding: 4px 8px;
  transition: all 0.3s ease-in-out;
}

p {
  color: #02203c;
  font-size: 16px;
}

pre {
  color: #02203c;
  background-color: #fff;
  box-shadow: 0 2px 4px rgb(50 50 93 / 10%);
  display: block;
  padding: 8px;
  border-radius: 4px;
  font-size: 13px;
}

code {
  color: #24405f;
  background: #fff;
  padding: 4px;
  border-radius: 2px;
}

body {
  background: #f8f9fa;
}
</style>
